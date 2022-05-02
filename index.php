<?
  require('db.php');
  /*
  $args = $_GET['i'];
  if($args){
    $req_uri=($_SERVER['REQUEST_URI']);
    $url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https:" : "http:") . "//{$_SERVER['HTTP_HOST']}/public_playlists{$req_uri}";
    echo file_get_contents($url);
    die();
  }
  */
?>
<!DOCTYPE html>
<html>
  <head>
    <style>
      body, html{
        margin: 0;
        background-color: #002;
        background-image: url(../LbEnV.jpg);
        background-repead: no-repeat;
        background-attachment: fixed;
        background-size: 100vw 100vh;
        min-height: 100vh;
        text-align: center;
        font-family: courier;
        color: #8f8;
        font-size: 24px;
        overflow-x: hidden;
      }
      .bg_overlay{
        position: fixed;
        width: 100vw;
        height: 100vh;
        z-index: 0;
        top: 0;
        left: 0;
        background: linear-gradient(45deg, #300b, #310b, #330b, #013b, #003b, #103b);
        background-repeat: no-repeat;
        background-attachment: fixed;
        background-size: 100vw 100vh;
        background-position: center center;
      }
      .headerLogo{
        position: absolute;
        left: 50%;
        z-index: 100;
        top: 20px;
        transform: translate(-50%);
        width: 400px;
        height: 100px;
        background-image: url(playlist_logo.png);
        background-size: 400px 100px;
        background-repeat: no-repeat;
        background-position: center center;
      }
      .main{
        width: 800px;
        display: inline-block;
        position: relative;
        width: 100%;
        min-height: 100vh;
        padding-top: 200px;
        margin-bottom: 100px;
      }
      button{
        border: none;
        border-radius: 5px;
        outline: none;
        background: #2fc6;
        color: #8fc;
        text-shadow: 2px 2px 2px #000;
        font-size: 1em;
        cursor: pointer;
        min-width: 150px;
        font-family: courier;
      }
      input[type=text]{
        font-size: 1em;
        background: #012;
        border: none;
        outline: none;
        font-family: courier;
        min-width: 400px;
        border-bottom: 1px solid #084;
        color: #ffc;
        text-align: center;
      }
      #playlists{
        padding-top:5px;
        width: 100%;
        display: inline-block;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
        min-height: 200px;
        width 80%;
      }
      .playlistButton{
        background: #28f6;
        margin: 20px;
        min-width: 500px;
        max-width: 500px;
      }
      .actionButtons{
        background-size: 25px 25px;
        background-position: center center;
        background-repeat: no-repeat;
        background-color: #0000;
        width: 25px;
        min-width: 25px;
        height: 25px;
        margin-left: 15px;
        margin-right: 15px;
      }
      .deleteButton{
        background-image: url(https://jsbot.cantelope.org/uploads/XeGsK.png);
      }
      .editButton{
        background-image: url(https://jsbot.cantelope.org/uploads/2cyWBg.png);
      }
      .unavailable{
        color: #f24!important;
        background: #300!important;
      }
      .available{
        color: #2f4!important;
        background: #031!important;
      }
      .disabledButton{
        background: #333;
        color: #aaa;
      }
      .playlistButtons{
        width: 700px;
        display: inline-block;
      }
    </style>
  </head>
  <body>
    <div class="bg_overlay"></div>
    <div class="headerLogo"></div>
    <div class="main">
      create playlist
      <br><br>
      <input spellcheck="false" id='title' autofocus type="text" placeholder="title" oninput="validate()" maxlength="25" onkeypress="submitMaybe(event)">
      <br><br>
      <button onclick="create()" disabled class="disabledButton" id="createButton">
        create
      </button>
      <br><br>
      <br><br>
      <div id="playlists"></div>
      <script>

        let cb = document.querySelector('#createButton')
        let titleInput = document.querySelector('#title')
        
        renderPlaylists=()=>{
          let playlistDiv = document.querySelector('#playlists')
          playlistDiv.innerHTML ='existing playlists<br>'
          playlists.map((v, i)=>{
            el = document.createElement('div')
            el.className = 'playlistButtons'
            link = document.createElement('button')
            link.className = 'playlistButton'
            link.innerHTML=v
            link.title = 'launch playlist'
            link.onclick=()=>{
              let a = document.createElement('a')
              //a.setAttribute('target', '_blank')
              a.href = './' + v
              a.style.display = 'none'
              document.body.appendChild(a)
              a.click()
              document.body.removeChild(a)
            }
            el.appendChild(link)

            //eb = document.createElement('button')
            //eb.className = 'editButton actionButtons'
            //eb.title = 'edit playlist'
            //eb.onclick=()=>{
            //}
            //el.appendChild(eb)

            db = document.createElement('button')
            db.className = 'deleteButton actionButtons'
            db.title = 'delete this playlist'
            db.onclick=()=>{
              if(confirm("\n\nAre you SURE you want to do this????\n\nthis action is irreversible!")){
                sendData = { playlist: v }
                fetch('delete.php',{
                  method: 'POST',
                  headers: {
                    'Content-Type': 'application/json',
                  },
                  body: JSON.stringify(sendData),
                }).then(res=>res.json()).then(data=>{
                  if(data[0]){
                    playlists=playlists.filter((q,j)=>j!=i)
                    setTimeout(()=>renderPlaylists(), 0)
                  }else{
                    alert("d'oh.\n\nthere was an error or summat")
                  }
                })
              }
            }
            el.appendChild(db)
            playlistDiv.appendChild(el)
          })
        }
        
        submitMaybe=e=>{
          if(e.keyCode==13 && !cb.disabled){
            create()
          }
        }
        
        create=()=>{
          let playlist = titleInput.value
          if(!!playlist && !cb.disabled){
            sendData = { playlist }
            fetch('create.php',{
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify(sendData),
            }).then(res=>res.json()).then(data=>{
              if(data[0]){
                titleInput.className = 'available'
                titleInput.value = ''
                cb.disabled = true
                cb.className = 'disabledButton'
                playlists = [playlist, ...playlists]
                renderPlaylists()
              }else{
                alert("d'oh.\n\nthere was an error or summat")
              }
            })
          }
        }
        
        validate=()=>{
          let title = titleInput.value
          if(!!title){
            sendData = { title }
            fetch('validate.php',{
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify(sendData),
            }).then(res=>res.json()).then(data=>{
              if(data[0]){
                titleInput.className = 'unavailable'
                cb.disabled = true
                cb.className = 'disabledButton'
              }else{
                cb.disabled = false
                cb.className = ''
                titleInput.className = 'available'
              }
            })
          } else {
            cb.disabled = true
            cb.className = 'disabledButton'
            titleInput.className = ''
          }
        }
        playlists = [
        <?
          $playlists = glob('./*', GLOB_ONLYDIR);
          forEach($playlists as $dir){
            $name = explode('./', $dir)[1];
            echo "'" . $name . "',";
          }
        ?>
        ]
        
        renderPlaylists()

      </script>
    </div>
  </body>
</html>