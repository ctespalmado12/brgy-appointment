
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" >
    <style>
        #hidden{
            visibility: hidden;
        }
        #buttonPrint{
            position: absolute;
            right: 10px;
        }
      
        #buttonPrint2{
            position: absolute;
            right: 140px;
        }
        #table{
            padding: 10px;
        }
    </style>
    <link rel="stylesheet" href="../../css/select.css">
</head>
<!-- <body onload="onloadEvent()" > -->
<body onload="changeTitle()">    

<nav class="navbar navbar-expand-lg navbar-dark bg-dark" >
  <button class="btn btn-dark mx-4 my-3 " onclick="history.back()"><svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" fill="currentColor" class="bi bi-arrow-left-square-fill" viewBox="0 0 16 16">
  <path d="M16 14a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v12zm-4.5-6.5H5.707l2.147-2.146a.5.5 0 1 0-.708-.708l-3 3a.5.5 0 0 0 0 .708l3 3a.5.5 0 0 0 .708-.708L5.707 8.5H11.5a.5.5 0 0 0 0-1z"/>
</svg></button>
<h2  class="container-fluid text-center">
    <a id="title" class="navbar-brand" href="#" style="font-size: 30px;">Navbar</a>
</h1>
</nav>
