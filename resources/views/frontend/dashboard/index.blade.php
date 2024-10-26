@extends('layouts.app')
@section('title')
Dashboard
@endsection


@section('head')



@endsection

@section('content')

<div id="app" v-cloak>


    <section class="hero" id="home">
        <div class="container">

            <h2 class="h1 hero-title">DIAGNOSIS KECANDUAN GAME ONLINE</h2>

            <p class="hero-text">
                Website ini dirancang dengan menggunakan metode <i><b>forward chaining</b></i> dan <i><b> certainty factor</b></i> untuk membantu masyarakat mengetahui tingkat kecanduan game online yang dapat berdampak negatif pada banyak aspek kehidupan, seperti perawatan diri, hubungan, sekolah, dan pekerjaan.
            </p>

            <div class="btn-group">
                <a href="/user/login" class="btn btn-primary">Diagnosis Sekarang</a>
            </div>

        </div>
    </section>


    <section class="popular" id="destination">
        <div class="container">

            <!-- <p class="section-subtitle">Lihat Ini</p> -->

            <h2 class="h2 section-title">Kecanduan Game Online</h2>

            <p class="section-text" style="text-align: left;">
                Organisasi Kesehatan Dunia atau World Health Organizations (WHO) resmi menetapkan Kecanduan Game (Gaming Disorder) ke dalam versi terbaru International Statistical Classification of Diseases-11 (ICD-11) mendefinisikan Kecanduan Game (Gaming Disorder) sebagai pola perilaku bermain game yang menyebabkan gangguan atau tekanan signifikan, meliputi:
                <br>
                • Tidak bisa mengendalikan keinginan bermain game
                <br>
                • Lebih memprioritaskan bermain game daripada kegiatan lain
                <br>
                • Terus bermain game meskipun ada konsekuensi negatif
                <br>
                Dimana pola bermain game bisa terus-menerus atau episodik, setidaknya selama 12 bulan, dapat secara online atau offline.
            </p>

        </div>
    </section>


    <section class="package" id="package">
        <div class="container">

            <!-- <p class="section-subtitle">Baca ini</p> -->

            <h2 class="h2 section-title">DAMPAK NEGATIF KECANDUAN GAME ONLINE</h2>

            <p class="section-text">
                Beberapa dampak negatif yang dapat di timbulkan karena kecanduan game online.
            </p>

            <ul class="package-list">

                <li>
                    <h2>MENTAL :</h2>
                    <br>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/orangstres.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Mental</h3>

                            <p class="card-text">
                                Depresi, kecemasan, distres emosional, gangguan suasana hati, halusinasi, insomnia, dan fobia sosial.
                            </p>
                        </div>
                    </div>
                </li>

                <li>
                    <h2>FISIK :</h2>
                    <br>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/ketegangan-mata.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Penurunan Ketajaman Penglihatan</h3>

                            <p class="card-text">
                                Cenderung berkacamata karena saat bermain tidak memperhatikan posisi, jarak pandang, pencahayaan yang baik, dan terpaku pada layar dalam jangka waktu lama tanpa istirahat. Cahaya biru dengan frekuensi tinggi dapat merusak retina.
                            </p>
                        </div>
                    </div>
                </li>


                <li>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/ambeien.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Ambeien</h3>

                            <p class="card-text">
                                Duduk dalam jangka waktu lama dapat menggangu sirkulasi darah dan menekan pembuluh darah vena disekitar anus, menimbulkan penonjolan pembuluh darah yang terasa panas, gatal dan nyeri.
                            </p>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/carpal-tunnel syndrom.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Carpal Tunnel Syndrome</h3>

                            <p class="card-text">
                                Mati rasa dan kesemutan di tangan dan lengan yang disebabkan oleh saraf terjepit di pergelangan tangan.
                            </p>
                        </div>
                    </div>
                </li>

                <li>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/menurunkan-metabolisme-1.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Perubahan Postur Tubuh</h3>

                            <p class="card-text">
                                Posisi tubuh yang tidak tepat saat menggunakan gadget, seperti duduk dengan punggung membungkuk atau leher condong ke depan, dapat menyebabkan masalah postur yang berpotensi menjadi kronis seiring waktu.
                            </p>
                        </div>
                    </div>
                </li>

                <!-- <li>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/kesehatan-mental.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Kesehatan Mental</h3>

                            <p class="card-text">
                                Kecanduan merupakan salah satu bentuk dari ganguan mental akibat berlebih-lebihan terhadap sesuatu. mengemukakan bahwa kecanduan merupakan perasaan yang sangat kuat terhadap sesuatu yang diinginkannya sehingga ia akan berusaha untuk mencari sesuatu yang sangat diinginkannya itu, , sehinga menyebabkan dampak negatif bagi indifidu, baik secara fisik maupun psikis.
                            </p>
                        </div>
                    </div>
                </li> -->

                <!-- BARU -->
                <li>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/gangguannutrisi.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Gangguan Nutrisi</h3>

                            <p class="card-text">
                                Perubahan pola istirahat (waktu tidur) dan pola makan akibat kurangnya kontrol diri waktu makan menjadi tidak teratur, dapat menjadi kurus (underweight) atau gemuk (overweight).
                            </p>
                        </div>
                    </div>
                </li>

                <li>
                    <h2>SOSIAL :</h2>
                    <br>
                    <div class="package-card">

                        <figure class="card-banner">
                            <img src="/gambar/sosial.jpg" alt="Experience The Great Holiday On Beach" loading="lazy">
                        </figure>

                        <div class="card-content">

                            <h3 class="h3 card-title">Sosial</h3>

                            <p class="card-text">
                                Mengurangi kemampuan sosial di kehidupan nyata disebabkan ketergantungan pada interaksi virtual game.
                            </p>
                        </div>
                    </div>
                </li>
    </section>




    <section class="gallery" id="gallery">
        <div class="container">

            <h2 class="h2 section-title">Tentang</h2>

            <!-- <p class="section-text">
                rancang bangun website ini, berdasarkan penelitian tugas akhir, dikarenakan banyak sekali yang bermain game online tanpa disadari tingkat kecanduan game online, supaya pengguna game online bisa mendiagnosis tingkat kecanduan game online secara langsung, dan sadar banyak sekali dampak negative dari kecanduan game online.
            </p> -->
            <p class="section-text" style="text-align: left;">
                Kecanduan Game Online adalah gangguan perilaku yang dapat memengaruhi kesehatan mental dan kualitas kehidupan seseorang.
                <br>
                Kecanduan Game Online dapat ditandai dengan beberapa ciri, seperti:
                <br>
                • Tidak dapat mengendalikan keinginan bermain game
                <br>
                • Lebih memprioritaskan bermain game daripada kegiatan lain
                <br>
                • Terus bermain game meskipun ada konsekuensi negatif
                <br>
                • Kehilangan minat pada pekerjaan, hobi, sekolah, dan hubungan
                <br>
                • Marah atau sedih ketika diingatkan untuk berhenti bermain game
                <br>
                • Rela berbohong demi mendapatkan alasan bermain game
                <br>
                • Stres, murung, atau cemas ketika tidak bisa bermain game
                <br>
                • Sulit berkonsentrasi terhadap berbagai hal selain game
                <br>
                • Tidak ragu untuk menghabiskan uang demi game
            </p>

            <!-- <ul class="gallery-list">

                <li class="gallery-item">
                    <figure class="gallery-image">
                        <img src="/gambar/kecanduangame.jpg" alt="Gallery image">
                    </figure>
                </li>

                <li class="gallery-item">
                    <figure class="gallery-image">
                        <img src="/gambar/kecanduangame2.jpg" alt="Gallery image">
                    </figure>
                </li>

                <li class="gallery-item">
                    <figure class="gallery-image">
                        <img src="/gambar/kecanduangame6.jpg" alt="Gallery image">
                    </figure>
                </li>

                <li class="gallery-item">
                    <figure class="gallery-image">
                        <img src="/gambar/kecanduangame4.jpg" alt="Gallery image">
                    </figure>
                </li>

                <li class="gallery-item">
                    <figure class="gallery-image">
                        <img src="/gambar/kecanduangame5.jpg" alt="Gallery image">
                    </figure>
                </li>

            </ul> -->

        </div>
    </section>

    <section class="gallery" id="gallery">
        <div class="container">
            <p class="section-text">
                Penting untuk diingat bahwa setiap individu berbeda-beda. Ada banyak faktor yang dapat memengaruhi tingkat kecanduan seseorang terhadap game online. Oleh karena itu, penanganan yang tepat harus disesuaikan dengan kondisi masing-masing individu.
                <br>
                Ingat, kamu tidak sendirian!
                <br>
                Banyak orang yang berhasil mengatasi kecanduan game online.
                <br>
                Jangan ragu untuk mencari bantuan.
            </p>
        </div>
    </section>

    <section class="cta" id="contact">
        <div class="container">

            <div class="cta-content">
                <!-- <p class="section-subtitle">Diagnosis Kecanduan Game Online</p> -->

                <h2 class="h2 section-title">"Mau tahu seberapa kecanduan kamu sama game online? Coba tes ini!"😊
                    <br>
                    <div class="btn-group">
                        <a href="/user/login" class="btn btn-secondary">👉 TES</a>
                    </div>
                </h2>


                <!-- <p class="section-text">
                    agar mengetahui tingkat kecanduan anda sekarang, jangan sampai terkena dampaknya.
                </p> -->


            </div>




        </div>

    </section>

</div>

@endsection

@section('pagescript')

@section('pagescript')
<script>
    let app = new Vue({
        el: '#app',
        data: {

        },
        methods: {

        }
    })
</script>
@endsection