<!DOCTYPE html>
<html>

<head>

    <title>Hospital Appointment Booking System</title>

    <style>

        *{
            margin:0;
            padding:0;
            box-sizing:border-box;
        }

        body{
            font-family:Arial;
            background:#f1f3f6;
        }

        .header{
            background:#0d6efd;
            color:white;
            padding:20px;
            text-align:center;
        }

        .container{
            display:flex;
            min-height:80vh;
        }

        .left{
            width:20%;
            background:#dee2e6;
            padding:20px;
        }

        .main{
            width:60%;
            padding:20px;
        }

        .right{
            width:20%;
            background:#dee2e6;
            padding:20px;
        }

        .card{
            background:white;
            padding:20px;
            border-radius:5px;
            margin-bottom:20px;
        }

        input,
        textarea,
        select{

            width:100%;
            padding:10px;
            margin-top:5px;
            margin-bottom:15px;
        }

        input[type=submit]{

            background:#0d6efd;
            color:white;
            border:none;
            cursor:pointer;
        }

        .error{
            background:#f8d7da;
            color:#842029;
            padding:10px;
            margin-bottom:10px;
        }

        .success{
            background:#d1e7dd;
            color:#0f5132;
            padding:10px;
            margin-bottom:10px;
        }

        .menu a{
            display:block;
            padding:10px;
            background:white;
            text-decoration:none;
            color:black;
            margin-bottom:10px;
        }

    </style>

</head>

<body>

<div class="header">

    <h2>Hospital Appointment Booking System</h2>

</div>

<div class="container">