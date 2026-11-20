<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Expresshipping & Logistics</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" integrity="sha384-G/EV+4j2dNv+tEPo3++6LCgdCROaejBqfUeNjuKAiuXbjrxilcCdDz6ZAVfHWe1Y" crossorigin="anonymous"></script>
<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>



    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"/>

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
            font-family:'Poppins', sans-serif;
        }

        body{
            background:#f5f5f5;
            overflow-x:hidden;
        }

        a{
            text-decoration:none;
        }

        .navbar{
            background:#fff;
            padding:20px 0;
        }

        .navbar-brand{
            font-size:30px;
            font-weight:700;
            color:#0a7868 !important;
        }

        .nav-link{
            color:#222 !important;
            font-size:14px;
            margin:0 10px;
            font-weight:500;
        }

        .hero{
        }

       

        .hero p{
            color:#666;
            line-height:1.8;
            margin-bottom:30px;
        }

        .btn-main{
            background:#0a7868;
            color:#fff;
            padding:14px 35px;
            border-radius:5px;
            font-weight:600;
            border:none;
        }

        .btn-main:hover{
            background:#066457;
            color:#fff;
        }

        .btn-outline-main{
            border:2px solid #0a7868;
            color:#0a7868;
            padding:12px 30px;
            font-weight:600;
            border-radius:5px;
        }

        .btn-outline-main:hover{
            background:#0a7868;
            color:#fff;
        }

        .hero img{
            width:100%;
        }

        .about-section{
            padding:100px 0;
            background:#0c7a6b;
            color:#fff;
        }

        .about-box{
            padding-right:40px;
        }

        .section-title{
            font-size:40px;
            font-weight:700;
            margin-bottom:20px;
        }

        .about-video{
            position:relative;
            overflow:hidden;
            border-radius:20px;
        }

        .about-video img{
            width:100%;
            border-radius:20px;
        }

        .play-btn{
            position:absolute;
            top:50%;
            left:50%;
            transform:translate(-50%,-50%);
            width:80px;
            height:80px;
            background:#fff;
            border-radius:50%;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#0a7868;
            font-size:30px;
            cursor:pointer;
        }

        .services{
            padding:100px 0;
            background:#fff;
        }

        .service-card{
            position:relative;
            overflow:hidden;
            border-radius:15px;
        }

        .service-card img{
            width:100%;
            height:350px;
            object-fit:cover;
        }

        .service-overlay{
            position:absolute;
            bottom:0;
            width:100%;
            padding:30px;
            background:linear-gradient(to top,rgba(0,0,0,.9),transparent);
            color:#fff;
        }

        .service-overlay h4{
            font-weight:600;
        }

        .ship-section{
            padding:100px 0;
            background:#fff;
        }

        .ship-card{
            background:#fff;
            border-radius:10px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            transition:.4s;
        }

        .ship-card:hover{
            transform:translateY(-10px);
        }

        .ship-card img{
            width:100%;
            height:220px;
            object-fit:cover;
        }

        .ship-content{
            padding:20px;
        }

        .charter{
            padding:100px 0;
            background:linear-gradient(to right,#086d60,#11a48f);
            color:#fff;
        }

        .step-box{
            margin-bottom:30px;
        }

        .step-number{
            width:45px;
            height:45px;
            border-radius:50%;
            background:#fff;
            color:#0a7868;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:700;
            margin-bottom:15px;
        }

        .testimonial{
            padding:100px 0;
            background:#fff;
        }

        .testimonial-card{
            background:#fff;
            border-radius:15px;
            padding:40px 30px;
            box-shadow:0 10px 30px rgba(0,0,0,.08);
            text-align:center;
        }

        .testimonial-card img{
            width:70px;
            height:70px;
            border-radius:50%;
            object-fit:cover;
            margin-bottom:20px;
        }

        footer{
            background:#0b0b0b;
            color:#fff;
            padding:30px 0;
            text-align:center;
        }

        @media(max-width:991px){

            .hero h1{
                font-size:40px;
            }

            .about-box{
                padding-right:0;
                margin-bottom:40px;
            }

        }

        @media(max-width:767px){

            .hero{
                text-align:center;
            }

            .hero h1{
                font-size:32px;
            }

            .section-title{
                font-size:30px;
            }

        }

.carousel-item .bg-video{
    top:0;
    left:0;
    width:100%;
    height:65vh;
    object-fit:cover;
}
.w-100{
width: 100vw;
height: 65vh;

    
}

.carousel-caption{
    padding: 30px; margin: 0; left: 0; flex-wrap:wrap;
    gap:20px;display: flex; justify-content; height: 100%;  line-height: -100px; align-items: center; background: rgba(0,0,0,0.5); width: 100%; bottom: 0;
}
    </style>
</head>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '3cdee84b5549494986e3f2d32837ba6eee376b07';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o._.push(arguments)};o._=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>

