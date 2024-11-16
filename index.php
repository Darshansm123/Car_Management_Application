<!DOCTYPE html>
<html>
<head>
    <title>Centered Buttons with Background Image</title>
    <style>
        body {
            background-image: url("images/loader-1.jpg");
            background-repeat: no-repeat;
            background-size: cover;
            display:;
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        .k1 {
            position: fixed;
            width: 50%;
            padding-left: 10%;
            display: flex;
            flex-direction: column;
            justify-content: space-between; /* Add this line */
        }
        p {
            color: white;
            padding-right: 20px;
            padding-top: 100px;
            font-size: large;
            text-align: center;
            font-family: 'Times New Roman', Times, serif;
            text: justify;
        }
        .img {
            padding-left: 860px;
            padding-top: 20px;
        }
        img {
            width: 90%;
            height: 15%;
            mix-blend-mode: darken;
        }
        span {
            font-size: 50px;
            color: YELLOW;
            font-style: normal;
        }
        .button-container {
            display: flex;
            justify-content: center;
            margin-top: 20px;
        }
        .button {
            background-color:blue;
            color: white;
            border: none;
            padding: 10px 30px;
            margin: 10px 0;
            cursor: pointer;
        }
    </style>
<body>
<span> <marquee><b>WELCOME TO CAR MANAGEMENT APPLICATION</b></marquee></span>
    <div class="k1"><h1> 
        <p>
            <span> Melodious Cars for Your's</span><br><br>
            <justify>Welcome to the Car Management Application your ultimate solution for seamless vehicle management! 🚗 
            Whether you own a single car or manage an entire fleet, this app is here to streamline your operations, 
            offering features like real-time monitoring, maintenance scheduling, and comprehensive analytics, all in one place. 
            Our user-friendly interface ensures that tracking your vehicle’s health, performance, and history has never been easier. 
            Say goodbye to manual record-keeping and hello to efficiency and peace of mind. Dive in, explore the features, 
            and start your journey towards smarter vehicle management today!</justify>
        </p>
        <div class="button-container">
            <button class="button" onclick="location.href='login.php'">Click here</button>
        </div>
    </div>
</body>
</html>