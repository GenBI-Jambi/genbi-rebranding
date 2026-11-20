(function(){"use strict";const{newsDetailUrl:m,pageUrl:y,renderShell:v}=window.GenBIApp,{observeFadeUp:S}=window.GenBIUI,{site:u,stats:k,programs:f,news:b,bpiMembers:$,publicEvents:x}=window.GenBIData,w=window.GenBIAPI,a={...u,...window.GenBISiteSettings||{},heroSlides:Array.isArray(window.GenBISiteSettings?.heroSlides)&&window.GenBISiteSettings.heroSlides.length?window.GenBISiteSettings.heroSlides:u.heroSlides,sidebar:{...u.sidebar||{},...window.GenBISiteSettings?.sidebar||{}},colors:{...u.colors||{},...window.GenBISiteSettings?.colors||{}}};v("home"),L(),M(),q(),I(),j(),C(),T(),H(),E(),G(),S();function L(){const e=document.querySelector("#hero-slider"),t=document.querySelector("#hero-dots"),r=document.querySelector("#hero-eyebrow"),n=document.querySelector("#hero-title"),s=document.querySelector("#hero-caption");if(!e)return;(e.dataset.ssr!=="true"||!e.children.length)&&(e.innerHTML=a.heroSlides.map((o,c)=>`
      <img src="${o.image}" alt="${o.caption}" class="hero-image hero-bg-image ${c===0?"is-active":""}" />
    `).join("")),t&&(t.dataset.ssr!=="true"||!t.children.length)&&(t.innerHTML=a.heroSlides.map((o,c)=>`<button class="h-2.5 w-2.5 rounded-full bg-white/40 transition hover:bg-white ${c===0?"bg-white":""}" aria-label="Slide ${c+1}" data-slide="${c}"></button>`).join(""));let i=0;const l=o=>{i=o;const c=a.heroSlides[i];e.querySelectorAll("img").forEach((g,h)=>g.classList.toggle("is-active",h===i)),t.querySelectorAll("button").forEach((g,h)=>{g.classList.toggle("bg-white",h===i),g.classList.toggle("bg-white/40",h!==i)}),r.innerHTML=`${window.GenBIApp.icon("sparkles","h-4 w-4")} ${c.eyebrow}`,n.textContent=c.title,s.textContent=c.caption};t.querySelectorAll("button").forEach(o=>o.addEventListener("click",()=>l(Number(o.dataset.slide)))),l(0),window.setInterval(()=>l((i+1)%a.heroSlides.length),6500)}function A(e){if(!e)return"Terbaru";const t=new Date(e);return Number.isNaN(t.getTime())?e:t.toLocaleDateString("id-ID",{day:"2-digit",month:"short",year:"numeric"})}function B(e="",t=150){const r=String(e).replace(/<[^>]*>/g,"").replace(/\s+/g," ").trim();return r.length>t?`${r.slice(0,t).trim()}\u2026`:r}function M(){const e=document.querySelector("#home-announcements"),t=document.querySelector("[data-announcement-section]")||e?.closest(".announcement-hero-section");if(!e||e.dataset.ssr==="true"&&e.children.length)return;const r=b.filter(n=>String(n.category||"").toLowerCase()==="pengumuman").slice(0,8);if(!r.length){t&&(t.hidden=!0);return}e.classList.toggle("is-centered",r.length<=2),e.innerHTML=r.map((n,s)=>`
    <a data-transition href="${m(n)}" class="announcement-card" aria-label="Baca pengumuman: ${n.title}">
      <span class="announcement-number">${String(s+1).padStart(2,"0")}</span>
      <span class="announcement-date">${A(n.date)}</span>
      <h3>${n.title}</h3>
      <p>${B(n.excerpt||(Array.isArray(n.body)?n.body.join(" "):""))}</p>
    </a>
  `).join("")}function q(){const e=document.querySelector("#stats-row");!e||e.dataset.ssr==="true"&&e.children.length||(e.innerHTML=k.map(t=>`
    <div class="fade-up">
      <p class="serif text-4xl font-semibold tracking-tight text-neutral-950">${t.value}</p>
      <p class="mt-2 text-sm leading-6 text-neutral-600">${t.label}</p>
    </div>
  `).join(""))}function P(e=""){const t=e.toLowerCase();return t.includes("siginjai")?d("bank"):t.includes("gentala")?d("chart"):t.includes("ggtc")?d("academic"):t.includes("leadership")?d("spark"):d("users")}function I(){const e=document.querySelector("#program-list");if(e){if(e.dataset.ssr==="true"&&e.children.length){p(e);return}e.innerHTML=f.map((t,r)=>{const n=Array.isArray(t.images)&&t.images.length?t.images:[a.heroSlides[0]?.image||"https://genbijambi.com/public/uploads/slider-1.png"],s=`<span class="blue-badge mx-auto mt-auto">${t.focus||"Program Kerja"}</span>`;return`
      <article class="editorial-slide-card program-slide-card" role="group" aria-roledescription="slide" aria-label="Program ${r+1} dari ${f.length}" data-program-slides='${JSON.stringify(n)}' style="--program-bg-image: url('${n[0]}');">
        <span class="slide-index">${String(r+1).padStart(2,"0")}</span>
        <span class="program-icon mx-auto">${window.GenBIApp.icon(t.icon_key||"sparkles","program-icon-svg")}</span>
        <p class="slide-kicker">${t.title}</p>
        <h3>${t.name}</h3>
        <p>${t.description}</p>
        ${s}
      </article>
    `}).join(""),p(e)}}function p(e){e.querySelectorAll(".program-slide-card").forEach(t=>{const r=t.querySelector("[data-program-icon]");if(r){const i=r.dataset.programIcon||"sparkles";r.innerHTML=window.GenBIApp.icon(i,"program-icon-svg")}else{const i=t.querySelector(".program-icon");i&&!i.innerHTML.trim()&&(i.innerHTML=window.GenBIApp.icon("sparkles","program-icon-svg"))}const n=JSON.parse(t.dataset.programSlides||"[]").filter(Boolean);if(!n.length||(t.style.setProperty("--program-bg-image",`url('${n[0]}')`),n.length<=1))return;let s=0;window.setInterval(()=>{s=(s+1)%n.length,t.style.setProperty("--program-bg-image",`url('${n[s]}')`)},4200)})}async function j(){const e=document.querySelector("#bpi-list");if(!e||e.dataset.ssr==="true"&&e.children.length)return;let t=$;try{const r=await w.getTeamList({per_page:200});t=Array.isArray(r?.bpi)&&r.bpi.length?r.bpi:t}catch{}e.innerHTML=t.map((r,n)=>`
    <article class="editorial-slide-card bpi-slide-card" role="group" aria-roledescription="slide" aria-label="Anggota BPI ${n+1} dari ${t.length}">
      <figure class="bpi-slide-photo">
        <span class="member-photo-skeleton" aria-hidden="true"></span>
        <img src="${r.photo||r.image}" alt="Foto ${r.name}" loading="lazy" onload="this.previousElementSibling.classList.add('is-hidden')" onerror="this.classList.add('is-hidden'); this.previousElementSibling.classList.remove('is-hidden')" />
      </figure>
      <div class="bpi-slide-content">
        <div class="bpi-slide-header">
          <h3 class="bpi-slide-name">${r.name}</h3>
          <p class="bpi-slide-role">${r.role}</p>
        </div>
        <div class="bpi-slide-footer">
          <span class="blue-badge"><span class="bpi-badge-dot" aria-hidden="true"></span>${r.commission||r.komsat||"GenBI Wilayah"}</span>
        </div>
      </div>
    </article>
  `).join("")}function d(e){const t={heart:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.49-2.01-4.5-4.5-4.5A4.48 4.48 0 0 0 12 6.36a4.48 4.48 0 0 0-4.5-2.61C5.01 3.75 3 5.76 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z"/></svg>',users:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.5c0-2.07-2.69-3.75-6-3.75s-6 1.68-6 3.75M9 12.75A3.75 3.75 0 1 0 9 5.25a3.75 3.75 0 0 0 0 7.5Zm12 6.75c0-1.74-1.9-3.2-4.45-3.62M15 5.58a3.75 3.75 0 0 1 0 6.84"/></svg>',chart:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 19.5h16.5M6.75 16.5v-6m5.25 6V6.75m5.25 9.75v-9"/></svg>',calendar:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3.75 9h16.5M5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v12A2.25 2.25 0 0 1 18 21H6a2.25 2.25 0 0 1-2.25-2.25v-12A1.5 1.5 0 0 1 5.25 5.25Z"/></svg>',bank:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.75 12 4.5l8.25 5.25M5.25 10.5h13.5M6.75 10.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5m3.5-7.5v7.5M4.5 18h15"/></svg>',academic:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 8.25 12 4.5l8.25 3.75L12 12 3.75 8.25Zm3 2.25v4.25c0 1.66 2.35 3 5.25 3s5.25-1.34 5.25-3V10.5"/></svg>',spark:'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="m12 3 1.76 5.45h5.74l-4.64 3.37 1.77 5.45L12 13.9l-4.63 3.37 1.77-5.45L4.5 8.45h5.74L12 3Z"/></svg>'};return t[e]||t.calendar}async function C(){const e=document.querySelector("#home-events");if(!e)return;if(e.dataset.ssr==="true"&&e.children.length){p(e);return}let t=x;try{const r=await w.getEventList();Array.isArray(r)&&r.length&&(t=r)}catch{}e.innerHTML=t.map((r,n)=>{const s=Array.isArray(r.images)&&r.images.length?r.images:[a.heroSlides[n%a.heroSlides.length]?.image||a.heroSlides[0]?.image||"https://genbijambi.com/public/uploads/slider-1.png"],i=r.type||r.category||"Agenda Komunitas",l=r.date||r.start_date||r.start||"-",o=r.description||r.excerpt||"";return`
    <article class="editorial-slide-card program-slide-card agenda-slide-card" role="group" aria-roledescription="slide" aria-label="Agenda ${n+1} dari ${t.length}" data-program-slides='${JSON.stringify(s)}' style="--program-bg-image: url('${s[0]}');">
      <span class="slide-index">${String(n+1).padStart(2,"0")}</span>
      <span class="program-icon mx-auto">${d(r.icon||"calendar")}</span>
      <p class="slide-kicker">${i}</p>
      <h3>${r.title}</h3>
      <p>${o}</p>
      <span class="blue-badge mx-auto mt-auto">${l}</span>
    </article>
  `}).join(""),p(e)}function H(){const e=document.querySelector("#home-contact-card");!e||e.dataset.ssr==="true"&&e.children.length||(e.innerHTML=`
    <div>
      <p class="eyebrow">Contact us</p>
      <h2 class="serif mt-3 text-3xl font-semibold tracking-tight text-neutral-950 md:text-5xl">Mau berkolaborasi dengan GenBI Jambi?</h2>
      <p class="mt-4 max-w-2xl text-base leading-7 text-neutral-600">Hubungi kami untuk informasi kegiatan, publikasi, kolaborasi, dan agenda komunitas.</p>
    </div>
    <div class="contact-prefooter-card">
      <p class="contact-label">Address</p>
      <p>${a.address}</p>
      <div class="mt-5 grid gap-2 text-sm">
        <a href="mailto:${a.email}">${a.email}</a>
        <a href="tel:${a.phone}">${a.phone}</a>
      </div>
      <a data-transition href="${y("contact")}" class="btn btn-primary mt-6 w-fit">Contact Us</a>
    </div>
  `)}function T(){const e=document.querySelector("#home-news");e&&(e.dataset.ssr==="true"&&e.children.length||(e.innerHTML=b.slice(0,3).map(t=>`
    <a data-transition href="${m(t)}" class="home-news-card">
      <figure class="home-news-media"><img src="${t.image}" alt="${t.title}" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" /></figure>
      <div class="home-news-copy">
        <div class="flex flex-wrap items-center gap-3 text-xs font-semibold text-neutral-500">
          <span class="text-blue-800">${t.category}</span><span>${t.date}</span><span>${t.readTime}</span>
        </div>
        <h3 class="serif text-2xl font-semibold leading-tight tracking-tight text-neutral-950 md:text-3xl">${t.title}</h3>
        <p class="text-base leading-7 text-neutral-600">${t.excerpt}</p>
      </div>
    </a>
  `).join("")))}function G(){document.querySelectorAll("[data-carousel]").forEach(e=>{const t=e.querySelector(".horizontal-carousel"),r=e.querySelector("[data-carousel-prev]"),n=e.querySelector("[data-carousel-next]");if(!t||!r||!n)return;const s=()=>{const i=t.querySelector(".editorial-slide-card, .announcement-card");if(!i)return t.clientWidth*.85;const l=window.getComputedStyle(t),o=parseFloat(l.columnGap||l.gap||"0")||0;return i.getBoundingClientRect().width+o};r.addEventListener("click",()=>t.scrollBy({left:-s(),behavior:"smooth"})),n.addEventListener("click",()=>t.scrollBy({left:s(),behavior:"smooth"}))})}function E(){const e=document.querySelector("#video-modal"),t=e?.querySelector(".modal-panel"),r=document.querySelector("#open-video"),n=document.querySelector("#close-video"),s=document.querySelector("#profile-video");if(!e||!r||!n||!s)return;const i=()=>{s.src=a.videoResourceUrl,e.classList.remove("hidden"),window.setTimeout(()=>t.classList.add("is-open"),20)},l=()=>{t.classList.remove("is-open"),window.setTimeout(()=>{e.classList.add("hidden"),s.src=""},160)};r.addEventListener("click",i),n.addEventListener("click",l),e.addEventListener("click",o=>{o.target===e&&l()})}})();
