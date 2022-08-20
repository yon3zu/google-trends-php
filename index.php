<!DOCTYPE html>
        <html lang="en">
        <!--- https://github.com/yon3zu/google-search-trends -->
        <!-- copyright bayu santoso -->
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Google Search Trends</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        </head>
        
        <body>
            <div class="container">
                <br>
                <nav class="navbar navbar-dark bg-dark">
                    <div class="container-fluid">
                        <a class="navbar-brand" href="index.php">
                            Google Trends
                        </a>
                        <a class="nav-link btn-dark" href="index.php?geo=ID">Indonesia</a>
                        <a class="nav-link btn-dark" href="index.php?geo=MY">Malaysia</a>
                        <a class="nav-link btn-dark" href="index.php?geo=SG">Singapore</a>
                        <a class="nav-link btn-dark" href="index.php?geo=AU">Australia</a>
                        <a class="nav-link btn-dark" href="index.php?geo=GB">United Kingdom</a>
                        <a class="nav-link btn-dark" href="index.php?geo=US">United States</a>
                        <a class="nav-link btn-dark" href="index.php?geo=JP">Japan</a>
                        <a class="nav-link btn-dark" href="index.php?geo=global">Global</a>
                        <a class="nav-link btn-dark" href="index.php?geo=FR">France</a>
                    </div>
                </nav>
                <br>
                <?php
                    error_reporting(0);
                    $geo = $_GET['geo'];
                    //link akses data api
                    if ($geo == null) {
                      $geo = "ID";
                    }
                    $req="https://trends.google.co.id/trends/trendingsearches/daily/rss?geo=$geo";
                    //ambil isi konten
                    $temp=file_get_contents($req);
                    //menjadikan format xml string
                    $xml=simplexml_load_string($temp);
                    //inisalisasi penomoran untuk tiap list
                    $no = 1;
        
                    //melakukan perluangan, ambil data yang berada di dalam tag channel di dalam item
                    foreach($xml->channel->item as $data){
                ?>
                <!-- membuat card bootstrap  -->
                <div class="card mb-3">
                    <div class="row">
                        <div class="col-md-1">
                            <h2 class="p-3"><?php echo $no++;?></h2>
                        </div>
                        <div class="col-md-2">
                            <div class="card-body">
                                <!-- menampilkan judul pencarian -->
                                <h5 class="card-title"><?=$data->title?></h5>
                                <!-- menampilkan tanggal pencarian  -->
                                <h6 class="card-subtitle mb-2 text-muted">
                                    <?=$data->pubDate?>
                                </h6>
                                <p class="card-text">
                                    <?php 
                                    //membuat data description menjadi array dengan menggunakan fungi explode
                                    $label = explode(', ', $data->description); 
        
                                    //melakukan perulangan
                                    foreach($label as $l){ 
                                ?>
                                    <!-- tampilkan data descriptin di dalam badge bootsrap  -->
                                    <span class="badge bg-dark"><?php echo $l;?></span>
        
                                    <?php } ?>
        
                                </p>
                                <!-- mengambil data jumalh pencarian contoh: 200.000 -->
                                <!-- tag yang diambil <ht:approx_traffic> sedangkan cara menampilkannya seperti gambar di bawah ini  -->
                                <p class="card-text"><small class="text-muted"><?=$data->children('ht', true)->approx_traffic?>
                                        Pencarian</small></p>
                            </div>
                        </div>
                        <div class="col-md-9 d-flex justify-content-end">
                            <?php
                                // melakukan perulangan contoh berita di dalam sebuah pencarian 
                                // tag yang diambil adalah <ht:news_item> sedangkan cara menampilkannya seperti gambar di bawah ini 
                               foreach($data->children('ht', true)->news_item as $data){
                            
                            ?>
                            <div class="card m-2" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">
                                    <!-- // berikut ini judul dari sebuah artikel berita  -->
                                    <!-- // tag yang diambil adalah <ht:news_item_title> sedangkan cara menampilkannya seperti gambar di bawah ini  -->
                                        <?=$data->children('ht', true)->news_item_title?>
                                    </h5>
                                    <h6 class="card-subtitle mb-2 text-muted">
                                    <!-- // mengambil sumber berita, seperti detik, cnn dll
                                    // tag yang diambil adalah <ht:news_item_source> sedangkan cara menampilkannya seperti gambar di bawah ini  -->
                                        <?=$data->children('ht', true)->news_item_source?>
                                    </h6>
                                    <p class="card-text">
                                    <!-- // mengambil isi potongan berita
                                    // tag yang diambil adalah <ht:news_item_snippet> sedangkan cara menampilkannya seperti gambar di bawah ini  -->
                                        <?=$data->children('ht', true)->news_item_snippet?></p>
        
                                        <!-- menampilakan link menuju berita  -->
                                    <a href="<?=$data->children('ht', true)->news_item_url?>" class="btn btn-dark">Read More</a>
                                </div>
        
                            </div>
        
                            <!-- penutup foreach -->
                           <?php } ?>
                        </div>
                    </div>
        
                </div>
                <!-- penutup foreach yang paling atas  -->
                <?php } ?>
            </div>
        
        </body>
        
        </html>