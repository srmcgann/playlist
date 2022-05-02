<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=.7">
    <meta name="og:image" content="VCNU1.png">
		<link rel="shortcut icon" type="image/png" href="VCNU1.png"/>
    <title><?echo $playlistName = ($a = explode('/',getcwd()))[sizeof($a)-1] . ' playlist';?></title>
		<meta name="description" content="<?echo $playlistName;?>">
    <style>
      body, html{
        border: 0;
        background: linear-gradient(45deg, #000, #103);
        background-repead: no-repeat;
        background-attachment: fixed;
        height: 100vh;
        overflow: hidden;
        color:#cfd;
        font-size: .85em;
        font-family: courier;
      }
      .main{
        text-align: center;
        padding-bottom: 100px;
        z-index: 10;
        top: 0;
        left: 0;
        width: 100%;
      }
      .deleteButton{
        border: none;
        border-radius: 5px;
        outline: none;
        cursor: pointer;
        background-size: 35px 35px;
        background-position: center center;
        background-repeat: no-repeat;
        background-color: #0000;
        width: 35px;
        min-width: 25px;
        height: 35px;
        margin-left: 15px;
        margin-right: 15px;
        background-image: url(https://jsbot.cantelope.org/uploads/XeGsK.png);
      }
      .songButton{
        border-radius: 15px;
        display: inline-block;
        border: none;
        max-width: calc(75% - 0px);
        min-width: calc(75% - 0px);
        cursor: pointer;
        color: #afa;
        font-family: courier;
        font-size: 2em;
        padding: 10px;
        margin: 10px;
        whitespace: normal;
        text-align: left;
        text-shadow: 1px 1px 2px #000;
        padding-left: 60px;
        padding-right: 5px;
        background-image: url(2ftyk1.png);
        background-color: #004;
        background-repeat: no-repeat;
        background-position: 10px center;
        background-size: 45px 45px;
      }
      #playerFrame{
        left: 0;
        top:0;
        margin-top:0px;
        width:100%;
        min-width: 600px;
        max-width: 70%;
        height:290px;
        border:none;
        vertical-align:top;
      }
      input[type=checkbox]{
        transform: scale(2.0);
      }
      label{
        font-size: 2em;
      }
      .jumpButton{
        position: fixed;
        left: 0;
        top: 0;
        margin: 20px;
        border-radius: 5px;
        font-size: 16px;
        background: #408;
        color: #fff;
        border: none;
        z-index: 20;
        font-weight: 900;
        font-family: courier;
        cursor: pointer;
      }
      .trackButtons{
        margin-top: 0px;
        width:100%;
        min-width: 600px;
        max-width: 75%;
        display: inline-block;
        max-height: calc(100vh - 370px);
        overflow-x: hidden;
        overflow-y: auto;
      }
      .modal{
        position: fixed;
        width: 100vw;
        height: calc(100vh - 100px);
        padding-top: 100px;
        text-align: center;
        background: #012c;
        display: none;
        overflow-y: auto;
      }
      #addTrackModal{
        z-index: 100;
      }
      .addTrackButton{
        font-size: 16px;
        margin: 10px;
        width: calc(100% - 100px);
      }
      .normalButtons{
        border: none;
        border-radius: 5px;
        outline: none;
        background: #2fc6;
        color: #8fc;
        text-shadow: 2px 2px 2px #000;
        font-size: 24px;
        cursor: pointer;
        min-width: 150px;
        font-family: courier;
      }
      .closeButton{
        background: #300;
        color: #fcc;
      }
      .searchButton{
        background: #032;
        color: #8fc;
      }
      input[type=text]{
        font-size: 24px;
        background: #000;
        border: none;
        outline: none;
        font-family: courier;
        min-width: 400px;
        border-bottom: 1px solid #084;
        color: #ffc;
        text-align: center;
      }
      .audiocloud{
        background: #206;
      }
      a{
        color: #ff0;
        text-decoration: none;
        background: #002;
        border-radius: 5px;
        padding: 5px;
      }
      #searchResults{
        width: 800px;
        padding: 20px;
        padding-bottom: 100px;
        font-size: 24px;
        display: inline-block;
      }
      #audiocloudDiv{
        backgroundL #f00;
      }
      #youtubeDiv{
        backgroundL #04f;
      }
      .youtubeLogo{
        color: #fff;
        background: #f00;
        font-weight: 900;
      }
    </style>
  </head>
  <body>
    <div id="addTrackModal" class="modal">
      <input
        spellcheck="false"
        type="text"
        autofocus
        onkeypress="searchMaybe(event)"
        id="searchBar"
      ><br>
      <div style="width: 300px;text-align: left;margin-left:auto;margin-right: auto;margin-top: 20px;">
        <label for="exact">
          <input type="checkbox" id="exact" oninput="toggleExact(this)">
          exact match
        </label><br>
        <label for="allWords">
          <input type="checkbox" checked id="allWords" oninput="toggleAllWords(this)">
          include all words
        </label>
      </div>
      <br><br><br>
      <button onclick="search()" class="searchButton normalButtons">search</button><br><br>
      <button onclick="closeModal('#addTrackModal')" class="closeButton normalButtons">close</button><br><br>
      <div id="searchResults"></div>
    </div>
    <div class="main">
      <br>
      <label for="shuffle">
        <input type="checkbox" id="shuffle" oninput="toggleShuffle(this)">
        shuffle
      </label>
      <button onclick="showModal('#addTrackModal')" class="normalButtons">add track(s)</button>
      <br><br>
      <iframe
        id="playerFrame"
        src=""
      ></iframe>
      <br><br>
      <div class="trackButtons"></div>
    <script>
      Rn=Math.random
      userInteracted = false
      let searchBar = document.querySelector('#searchBar')
      let searchResults = document.querySelector('#searchResults')
      let allWords = document.querySelector('#allWords')
      let exact = document.querySelector('#exact')

      window.onkeydown=e=>{
        if(e.keyCode==27) closeModal('#addTrackModal')
      }

      searchMaybe=e=>{
        let sparam = searchBar.value
        if(e.keyCode==13 && sparam !== ''){
          search()
        }
      }
      
      addTrack=(id, source)=>{
        sendData = { id, source }
        searchResults.innerHTML = ''
        searchBar.value = ''
        closeModal('#addTrackModal')
        setTimeout(()=>{
          alert("\nthe track is being added.\n\nif successful, it will appear in your list shortly, as if by magic...")
        }, 0)
        fetch('addTrack.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        }).then(res=>res.json()).then(data=>{
          if(data[0]){
            tracks = ['/tracks/' + data[1], ...tracks]
            renderTracks()
          } else {
            alert('there was a problem adding the track!')
          }
        })
      }
      
      search=()=>{
        let sparam = searchBar.value
        sparam = searchBar.value
        if(!sparam) return
        sendData = { sparam, allWords, exact }
        fetch('search.php',{
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify(sendData),
        }).then(res=>res.json()).then(data=>{
          if(data[0]){
            res = JSON.parse(data[2])
            searchResults.innerHTML=`search results [<a href="https://audiocloud.dweet.net" target="_blank">audiocloud</a>]<br><div id="audiocloudDiv">`
            res[0].map(v=>{
              searchResults.innerHTML+=`<button class="normalButtons addTrackButton audiocloud"
                onclick="addTrack(`+v.id+`, 'audiocloud')"
                title="description: `+v.description+`">`+v.trackName+`</button>`
            })
            if(!res.length) searchResults.innerHTML+='no results'
            searchResults.innerHTML+='<br>search results ['
            let sp = document.createElement('span')
            sp.innerHTML='youtube'
            sp.className='youtubeLogo'
            searchResults.appendChild(sp)
            searchResults.innerHTML+=`]<br><br><br></div><div id="youtubeDiv">`
            searchResponse = data[1]
            searchResponse.map(v=>{
              searchResults.innerHTML+=`<button class="normalButtons addTrackButton"
                onclick="addTrack('`+v.id+`', 'youtube')"
                title="description: `+v.description+`">`+v.snippet.title+`</button>`
            })
            if(!searchResponse.length) searchResults.innerHTML+='no results'
            searchResults.innerHTML+='</div>'
          } else {
            alert('there was a problem searching... :(')
          }
        })
      }
      
      closeModal=modal=>{
        document.querySelector(modal).style.display='none'
      }
      
      showModal=modal=>{
        document.querySelector(modal).style.display='block'
        searchBar.focus()
      }
      
      scrollUp=()=>{
        window.scrollTo(0, 0)
      }
      
      deleteTrack=idx=>{
        let trackName=(l=decodeURI(tracks[idx]).split('/'))[l.length-1]
        if(confirm("\n\nAre you SURE you want to do this????\n\nthis action is irreversible!")){
          sendData = { trackName }
          fetch('deleteTrack.php',{
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
            },
            body: JSON.stringify(sendData),
          }).then(res=>res.json()).then(data=>{
            if(data[0]){
              tracks=tracks.filter((q,j)=>j!=idx)
              setTimeout(()=>renderTracks(), 0)
            }else{
              alert("d'oh.\n\nthere was an error or summat")
            }
          })
        }
      }
      
      playTrack=idx=>{
        let el
        (el = document.querySelector('#playerFrame'))
        postMessage(JSON.stringify({'userInteracted': userInteracted}))
        el.src = 'https://audioplayer.dweet.net/' + window.location.href + tracks[idx] + (userInteracted ? '?autoplay' : '')
        
      }
      
      curIDX = 0

      renderTracks=()=>{
        let trackDiv = document.querySelectorAll('.trackButtons')[0]
        trackDiv.innerHTML = ''
        tracks.map((v, i)=>{
          let tb = document.createElement('button')
          tb.className = 'songButton'
          tb.onclick = () =>{
            playTrack(curIDX = i)
          }
          tb.innerHTML = decodeURI(v.replaceAll('/tracks/', '')) + '<br>'
          trackDiv.appendChild(tb)
          let db = document.createElement('button')
          db.className = 'deleteButton'
          db.onclick = () =>{
            deleteTrack(curIDX = i)
          }
          trackDiv.appendChild(db)
        })
      }

      postMessage=msg=>{
        let el = document.querySelector('#playerFrame')
        if(el.src.indexOf('https://audioplayer.dweet.net') != -1){
          el.contentWindow.postMessage(msg, 'https://audioplayer.dweet.net')
        }
      }
      window.addEventListener('message', e => {
        const key = e.message ? 'message' : 'data';
        const data = e[key];
        switch(data){
          case 'ended':
            playTrack(shuffle ? Rn()*tracks.length|0 : curIDX=(curIDX+1)%tracks.length)
          break
          case 'playing':
            userInteracted = true
          break
        }
      },false);

      shuffle = false
      exact = false
      allWords = true

      toggleShuffle=e=>{
        shuffle = e.checked
      }

      toggleExact=e=>{
        exact = e.checked
      }

      toggleAllWords=e=>{
        allWords = e.checked
      }

      tracks = [
        <?
          foreach (glob("tracks/*.mp3") as $filename) {
            $file = str_replace('/','', str_replace("tracks/", "", $filename));
            echo "'/tracks/".rawurlencode("$file") . "'" . ",";
          }
        ?>
      ]
      
      vid = document.createElement('video')
      vid.style.position = 'absolute'
      vid.style.opacity = '0'
      vid.style.top = '0'
      vid.style.zIndex=-1;
      vid.style.mouseEvents = 'none'
      vid.style.left = '0'
      document.body.appendChild(vid)
      vid.src='sleepBuster.mp4'
      vid.loop=true
      vid.muted=true
      vid.play()
      renderTracks()
      playTrack(shuffle ? Rn()*tracks.length|0 : curIDX=(curIDX)%tracks.length)
    </script>
    </div>
  </body>
</html>
