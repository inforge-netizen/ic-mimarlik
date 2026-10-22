document.body.classList.add('theme-ready');

(function(){
  const header=document.querySelector('.ma-header');
  const toggle=document.querySelector('.ma-toggle');
  const nav=document.querySelector('.ma-nav');
  const phone=header?.querySelector('.ma-header-phone');
  const tools=header?.querySelector('.ma-header-right-tools');
  const phoneMarker=phone?document.createComment('phone original position'):null;
  const toolsMarker=tools?document.createComment('header tools original position'):null;
  if(phone&&phoneMarker)phone.parentNode.insertBefore(phoneMarker,phone);
  if(tools&&toolsMarker)tools.parentNode.insertBefore(toolsMarker,tools);
  const mobileHeader=window.matchMedia('(max-width: 900px)');
  function placeHeaderTools(){
    if(!header||!nav)return;
    if(mobileHeader.matches){
      // Mobile navigation always starts closed; contact methods live at its bottom.
      header.classList.remove('is-open');
      toggle?.setAttribute('aria-expanded','false');
      if(toggle)toggle.textContent='☰';
      if(phone)nav.appendChild(phone);
      if(tools)nav.appendChild(tools);
    }else{
      if(phone&&phoneMarker?.parentNode)phoneMarker.parentNode.insertBefore(phone,phoneMarker.nextSibling);
      if(tools&&toolsMarker?.parentNode)toolsMarker.parentNode.insertBefore(tools,toolsMarker.nextSibling);
      header.classList.remove('is-open');
      toggle?.setAttribute('aria-expanded','false');
      if(toggle)toggle.textContent='☰';
    }
  }
  placeHeaderTools();
  if(mobileHeader.addEventListener)mobileHeader.addEventListener('change',placeHeaderTools);else mobileHeader.addListener(placeHeaderTools);
  toggle?.addEventListener('click',()=>{
    const open=header?.classList.toggle('is-open') ?? false;
    toggle.setAttribute('aria-expanded',open?'true':'false');
    toggle.textContent=open?'×':'☰';
  });
  nav?.querySelectorAll('.ma-submenu-toggle').forEach(btn=>btn.addEventListener('click',(e)=>{
    e.preventDefault(); e.stopPropagation();
    const item=btn.closest('.ma-menu-item');
    const open=item?.classList.toggle('is-submenu-open');
    btn.setAttribute('aria-expanded',open?'true':'false');
  }));
  nav?.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>{
    header?.classList.remove('is-open');
    toggle?.setAttribute('aria-expanded','false');
    if(toggle) toggle.textContent='☰';
    nav?.querySelectorAll('.is-submenu-open').forEach(item=>item.classList.remove('is-submenu-open'));
    nav?.querySelectorAll('.ma-submenu-toggle').forEach(btn=>btn.setAttribute('aria-expanded','false'));
  }));

  // v2.76.82 — Quiet Luxury slider: independent editorial copy and architectural image layers.
  const hero=document.querySelector('.ma-quiet-hero');
  const slides=hero?[...hero.querySelectorAll('.ma-slide')]:[];
  const visuals=hero?[...hero.querySelectorAll('.ma-quiet-image')]:[];
  const dots=hero?[...hero.querySelectorAll('.ma-dots button')]:[];
  const current=hero?.querySelector('.ma-current');
  const reduceMotion=window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
  let index=Math.max(0,slides.findIndex(el=>el.classList.contains('is-active'))),timer=null,locked=false;
  function updateUI(){
    dots.forEach((el,n)=>{el.classList.toggle('is-active',n===index);el.setAttribute('aria-current',n===index?'true':'false');});
    if(current)current.textContent=String(index+1).padStart(2,'0');
    slides.forEach((el,n)=>{el.setAttribute('aria-hidden',n===index?'false':'true');el.classList.toggle('is-active',n===index);});
    visuals.forEach((el,n)=>el.classList.toggle('is-active',n===index));
  }
  function paint(target){
    if(!slides.length||locked)return;
    const next=(target+slides.length)%slides.length;if(next===index)return;
    const old=slides[index],fresh=slides[next];locked=true;
    old?.classList.add('is-leaving');
    index=next;updateUI();
    window.setTimeout(()=>{old?.classList.remove('is-leaving');locked=false;},reduceMotion?0:780);
  }
  function stop(){if(timer){clearInterval(timer);timer=null;}}
  function start(){stop();if(slides.length>1&&!reduceMotion&&!document.hidden&&!hero?.matches(':hover'))timer=setInterval(()=>paint(index+1),7200);}
  function go(i){paint(i);start();}
  hero?.querySelector('.ma-next')?.addEventListener('click',()=>go(index+1));
  hero?.querySelector('.ma-prev')?.addEventListener('click',()=>go(index-1));
  dots.forEach((d,i)=>d.addEventListener('click',()=>go(i)));
  hero?.addEventListener('mouseenter',stop);hero?.addEventListener('mouseleave',start);
  document.addEventListener('visibilitychange',()=>document.hidden?stop():start());
  updateUI();start();

  const main=document.getElementById('maGalleryMain');
  const galleryLink=document.getElementById('maGalleryLink');
  const title=document.getElementById('maGalleryTitle');
  const meta=document.getElementById('maGalleryMeta');
  document.querySelectorAll('.ma-thumb').forEach(btn=>btn.addEventListener('click',()=>{
    if(!main)return;
    main.classList.add('is-changing');
    setTimeout(()=>{
      main.src=btn.dataset.image||'';
      main.alt=btn.dataset.title||'';
      if(galleryLink){galleryLink.href=btn.dataset.url||galleryLink.href;galleryLink.setAttribute('aria-label',(btn.dataset.title||'Proje')+' projesini incele');}
      if(title)title.textContent=btn.dataset.title||'';
      if(meta)meta.textContent=btn.dataset.meta||'';
      document.querySelectorAll('.ma-thumb').forEach(x=>x.classList.remove('is-active'));
      btn.classList.add('is-active');
      main.classList.remove('is-changing');
    },180);
  }));
})();

// v2.74.62 — smooth back-to-top
(function(){
  const top=document.querySelector('.ma-back-top');
  if(!top)return;
  const toggle=()=>top.classList.toggle('is-visible',window.scrollY>420);
  toggle();
  window.addEventListener('scroll',toggle,{passive:true});
  top.addEventListener('click',e=>{e.preventDefault();window.scrollTo({top:0,behavior:'smooth'});});
})();

// v2.74.61 — smooth in-page navigation
document.querySelectorAll('a[href^="#"]').forEach(a=>a.addEventListener('click',e=>{
  const id=a.getAttribute('href');
  if(!id||id==='#')return;
  const target=document.querySelector(id);
  if(target){e.preventDefault();target.scrollIntoView({behavior:'smooth',block:'start'});}
}));


// Premium scroll polish
(function(){
  const header=document.querySelector('.ma-header');
  const progress=document.createElement('div'); progress.className='ma-scroll-progress'; document.body.appendChild(progress);
  const update=()=>{
    const max=document.documentElement.scrollHeight-window.innerHeight;
    const ratio=max>0?window.scrollY/max:0;
    progress.style.width=(ratio*100)+'%';
    header?.classList.toggle('is-scrolled',window.scrollY>24);
  };
  update(); window.addEventListener('scroll',update,{passive:true});
  const reveal=[...document.querySelectorAll('.ma-reveal')];
  if('IntersectionObserver' in window){
    const io=new IntersectionObserver(entries=>entries.forEach(e=>{if(e.isIntersecting){e.target.classList.add('is-visible');io.unobserve(e.target)}}),{threshold:.14});
    reveal.forEach(el=>io.observe(el));
  }else reveal.forEach(el=>el.classList.add('is-visible'));
})();


// v2.74.59 — custom language dropdown; preserves the previous trigger appearance and avoids native iOS sheet
(function(){
  const wrap=document.querySelector('.ma-language');
  const trigger=wrap?.querySelector('.ma-language-trigger');
  const menu=wrap?.querySelector('.ma-language-menu');
  if(!wrap||!trigger||!menu)return;
  const close=()=>{wrap.classList.remove('is-open');trigger.setAttribute('aria-expanded','false');};
  trigger.addEventListener('click',(e)=>{e.preventDefault();e.stopPropagation();const open=!wrap.classList.contains('is-open'); if(open){wrap.classList.add('is-open');trigger.setAttribute('aria-expanded','true');}else close();});
  menu.querySelectorAll('a').forEach(a=>a.addEventListener('click',()=>close()));
  document.addEventListener('click',(e)=>{if(!wrap.contains(e.target))close();});
  document.addEventListener('keydown',(e)=>{if(e.key==='Escape')close();});
})();


// v2.75.58 — accessible horizontal scrolling for the New Projects showcase
(function(){
  const track=document.getElementById('maNewProjectTrack');
  if(!track)return;
  const amount=()=>{const card=track.querySelector('.ma-new-project');return card?card.getBoundingClientRect().width+20:track.clientWidth*.8;};
  document.querySelector('.ma-new-project-prev')?.addEventListener('click',()=>track.scrollBy({left:-amount(),behavior:'smooth'}));
  document.querySelector('.ma-new-project-next')?.addEventListener('click',()=>track.scrollBy({left:amount(),behavior:'smooth'}));
  track.addEventListener('keydown',e=>{if(e.key==='ArrowRight'){e.preventDefault();track.scrollBy({left:amount(),behavior:'smooth'});}else if(e.key==='ArrowLeft'){e.preventDefault();track.scrollBy({left:-amount(),behavior:'smooth'});}});
})();
