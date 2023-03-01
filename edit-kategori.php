<?php
	session_start();
	include 'db.php';
	if($_SESSION['status_login'] != true){

	 	echo '<script>window.location="login.php"</script>';
	 } 

	 $kategori = mysqli_query($conn, "SELECT * FROM tb_category WHERE category_id = '".$_GET['id']."' ");

	 if(mysqli_num_rows($kategori) == 0) {
	 	echo '<script>window.location="data-kategori.php"</script>';
	 }

	 $k = mysqli_fetch_object($kategori);
 ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>InkeyFashion</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=DynaPuff&display=swap" rel="stylesheet">
</head>
<body>
	<!-- header -->
	<header>
		<div class="container">
			<h1><a href="dashboard.php">InkeyFashion</a></h1>
			<ul>
				<li><a href="dashboard.php">Dashboard</a></li>
				<li><a href="profil.php">Profil</a></li>
				<li><a href="data-kategori.php">Data Kategori</a></li>
				<li><a href="data-produk.php">Data Produk</a></li>
				<li><a href="keluar.php">Keluar</a></li>
			</ul>
		</div>
	</header>
	<!-- Content -->
	<div class="section">
		<div class="container">
			<h3>Edit Data Kategori</h3>
			<div class="box">
				<form action="" method="POST" enctype="multipart/form-data">
					<input type="text" name="nama" placeholder="Nama Kategori" class="input-control" value="<?php echo $k->category_name ?>"  required>
					<img src="images/kategori/<?php echo $k->category_image ?>" width="100px" >
					<input type="hidden" name="foto" value="<?php echo $k->category_image ?>">
					<input type="file" name="gambar" class="input-control">
					<input type="submit" name="submit" value="Submit" class="btn">
				</form>
				<?php
					if(isset($_POST['submit'])) {
						$nama = ucwords($_POST['nama']);
						$foto 		= $_POST['foto'];

						//data gambar yang baru
						$filename 	= $_FILES['gambar']['name'];
						$tmp_name 	= $_FILES['gambar']['tmp_name'];
						
						//jika admin ganti gambar
						if($filename != ''){
							$type1		= explode('.', $filename);
							$type2		= $type1[1];

							$newname = 'kategori'.time().'.'.$type2;

						//menampung data format file yang diizinkan
							$tipe_diizinkan = array('jpg', 'jpeg', 'png', 'gif');

							if(!in_array($type2, $tipe_diizinkan)){
							//jika format file tidak ada didalam file diizinkan
							echo '<script>alert("Format file tidak diizinkan")</script>';

							}else{
								unlink('images/kategori/'.$foto);
								move_uploaded_file($tmp_name, 'images/kategori/'.$newname);
								$namagambar = $newname;
							}

						}else{
							//jika admin tidak ganti gambar
							$namagambar = $foto;
						}


						$update = mysqli_query($conn, "UPDATE tb_category SET
												category_name = '".$nama."',
												category_image	= '".$namagambar."'
												WHERE category_id = '".$k->category_id."' ");
						if($update){
							echo '<script>alert("Edit Data Berhasil")</script>';
							echo '<script>window.location="data-kategori.php"</script>';
						}else{
							echo 'gagal'.mysqli_error($conn);
						}
					}
				?>
			</div>
		</div>
	</div>

	<!-- Footer -->
	<footer>
		<div class="container">
			<small>Copyright &copy; 2022 - InkeyFashion.</small>
		</div>
	</footer>
</body>
</html>