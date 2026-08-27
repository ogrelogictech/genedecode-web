var SAMPLE_VIDEO = "https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4";
var VIDEOS = [
  {id:"v40vxe6",t:"Envisioning Freedom — See It As Already Done!",c:"Deep Dive",d:"2:40:18",s:"156K views · most watched",p:34},
  {id:"v77jskw",t:"Time & Space Collapsing — Race to the Black Swan Event",c:"Deep Dive",d:"1:58:30",s:"Nino's Corner · Mar 21",p:0},
  {id:"v77x2a0",t:"Iran War Smokescreen — Liberating Tunnels & DUMBs",c:"Deep Dive",d:"1:42:10",s:"Mar 26",p:0},
  {id:"v786bgm",t:"Pearls of Wisdom — Golden Age Technologies",c:"Pearls of Wisdom",d:"18:42",s:"Series",p:0},
  {id:"v79kfo2",t:"Pearls of Wisdom — The Anunnaki",c:"Pearls of Wisdom",d:"22:15",s:"Series",p:70},
  {id:"v783gxe",t:"Pearls of Wisdom — Remote Viewing",c:"Pearls of Wisdom",d:"15:30",s:"Series",p:0},
  {id:"v7883sq",t:"Pearls of Wisdom — Walking with Christ and with God",c:"Pearls of Wisdom",d:"19:05",s:"Series",p:0},
  {id:"v784s4e",t:"Pearls of Wisdom — Vampires",c:"Pearls of Wisdom",d:"14:50",s:"Series",p:0},
  {id:"v780efe",t:"Pearls of Wisdom — Operation Epic Fury",c:"Pearls of Wisdom",d:"21:33",s:"Series",p:0},
  {id:"v7ardmw",t:"The General's Tent — Q&A with Gene Decode",c:"Q&A",d:"1:46:12",s:"Nino's Corner · May 28",p:62},
  {id:"v79neq0",t:"Humanitarians Changing the World — Group Q&A",c:"Q&A",d:"1:33:50",s:"Apr 13",p:0},
  {id:"v7axzmm",t:"Michelle Fielding interviews Gene Decode",c:"Interview",d:"1:12:40",s:"May 30",p:0},
  {id:"v7bcv98",t:"Dani Henderson (GSIC) interviews Gene Decode",c:"Interview",d:"58:20",s:"May 20",p:0},
  {id:"v7anj9s",t:"Truth Stream — Joe & Scott interview Gene Decode",c:"Interview",d:"1:22:05",s:"May 19",p:0},
  {id:"v78ldfy",t:"David Nino Rodriguez — Psychological War",c:"Interview",d:"1:05:48",s:"Apr 11",p:45},
  {id:"v77bwzq",t:"Michael Jaco interviews Gene Decode — The Golden Dome",c:"Interview",d:"1:18:24",s:"Mar 12",p:0},
  {id:"v78gxlg",t:"The Sovereign Soul Show interviews Gene Decode",c:"Interview",d:"1:09:12",s:"Apr 7",p:0}
];
function tag(c){return c==="Pearls of Wisdom"?"Pearls":c;}
function fromParam(){return document.body.dataset.page||"";}
function card(v,locked){
  var prog = v.p>0 ? '<div class="progress"><i style="width:'+v.p+'%"></i></div>' : '';
  var lock = locked ? '<span class="lock"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="5" y="11" width="14" height="9" rx="2"/><path d="M8 11V8a4 4 0 018 0v3"/></svg></span>' : '';
  return '<a class="card" href="watch.html?v='+v.id+'&from='+fromParam()+'"><div class="thumb" style="background-image:url(assets/vid/'+v.id+'.jpg)">'
    +'<span class="tag">'+tag(v.c)+'</span><span class="dur">'+v.d+'</span>'+lock+'</div>'+prog
    +'<div class="body"><h3>'+v.t+'</h3><p>'+v.s+'</p></div></a>';
}
function byCat(cat){return VIDEOS.filter(function(v){return v.c===cat});}
function render(id,list,locked){var e=document.getElementById(id);if(e)e.innerHTML=list.map(function(v){return card(v,locked)}).join("");}
// wire a .chips group to filter/sort a grid. mapFn(value) returns the list to show.
function wireChips(chipsSel, gridId, mapFn){
  var chips=[].slice.call(document.querySelectorAll(chipsSel+" .chip"));
  chips.forEach(function(c){
    c.addEventListener("click",function(){
      chips.forEach(function(x){x.classList.remove("on")});
      c.classList.add("on");
      render(gridId, mapFn(c.dataset.val || c.textContent.trim()));
    });
  });
}
