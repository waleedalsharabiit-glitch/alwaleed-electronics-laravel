<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>


    <style>
        .inputs{
           
            background-color:deepskyblue;
            width: 350px;
            height: 350px;
            position: relative;
            left: 550px;
            top:200px;

        }
        .inputs{
             display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
           

        }
        .inputs .lab{
            padding:10px;
            font-size: xx-large;
            font-family: 'Times New Roman', Times, serif;
            



        }

        .inputs .register{
            background-color: aquamarine;

        }

        .inputs .lab input{
            width:180px;
            height: 30px;
            border-radius: 10px;
        }


    </style>
</head>
<body>
    <form action="{{route('register')}}" method="POST">
        @csrf
 <div>
 
    <div class="inputs">
        <div class="in register">
   <h1>
Register
    </h1>
</div>
<div class="in lab">
<label for="name">Name:  </label>
<input type="text" id="name" name="name">
</div>
<div class="in lab">
<label for="email">Email:  </label>
<input type="text" id="email" name="email">
</div>
<div class="in lab">
<label for="password">Password:</label>
<input type="text" id="password" name="password">
</div>
<div class="in lab">
<input type="submit" id="ptn" name="ptn">

</div>

    </div>
 </div>



</form>
<script>

</script>
</body>
</html>