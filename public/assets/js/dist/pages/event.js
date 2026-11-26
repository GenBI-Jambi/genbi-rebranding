(function(){"use strict";const{renderShell:C}=window.GenBIApp,{createModalController:M,observeFadeUp:E,safeImage:B}=window.GenBIUI,I=window.GenBIAPI;C("event");const q=document.querySelector('#event-list[data-ssr="true"]'),P=document.body.dataset.ssr==="true"&&!document.querySelector("#event-list");function a(n){return String(n??"").replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}if(P)document.body.classList.add("page-ready"),E();else if(q){let o=function(e){e&&e.querySelectorAll(".open-event").forEach(r=>{r.addEventListener("click",t=>{t.preventDefault();const l=Number(r.dataset.id),v=r.dataset.slug||"";l>0&&_({id:l,slug:v},n)})})},S=function(e){const r=window.GenBIUI?.safeImage||(w=>w||"https://genbijambi.com/public/uploads/slider-1.png"),t=a(e.start_date||"")+(e.end_date&&e.end_date!==e.start_date?" \u2013 "+a(e.end_date):""),l=encodeURIComponent(e.slug||e.id||""),v=a(e.status||"Event"),g=e.status==="Upcoming"?"upcoming":"",x=e.image||e.photo||e.banner||"https://genbijambi.com/public/uploads/slider-1.png";return`
      <article class="event-card">
        <div class="event-card-image">
          <img src="${r(x)}" alt="${a(e.title||"")}" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
          <span class="event-card-badge ${g}">${v}</span>
        </div>
        <div class="event-card-body">
          <div class="event-date-row">
            <svg class="h-3.5 w-3.5 text-[#114b9a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
              <line x1="16" x2="16" y1="2" y2="6" />
              <line x1="8" x2="8" y1="2" y2="6" />
              <line x1="3" x2="21" y1="10" y2="10" />
            </svg>
            <span>${t}</span>
          </div>
          <h3 class="event-card-title">${a(e.title||"")}</h3>
          <p class="event-card-excerpt">${a(e.excerpt||"")}</p>
          <div class="event-card-location">
            ${e.location?`
              <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>
              <span class="truncate">${a(e.location)}</span>
            `:""}
          </div>
        </div>
        <div class="event-card-footer">
          <a href="/event/${l}" class="btn-event-detail open-event" data-id="${Number(e.id)}" data-slug="${a(e.slug||"")}" aria-label="Lihat detail event ${a(e.title||"")}">
            <span>Lihat Detail Agenda</span>
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
            </svg>
          </a>
        </div>
      </article>
    `},s=function(e,r){if(!h)return;const t=Number(e.total_pages||e.totalPages||1),l=Number(e.page||1);if(t<=1){h.innerHTML="";return}let v="";l>1?v+=`<button type="button" class="pager-button" data-page="${l-1}">Sebelumnya</button>`:v+='<span class="pager-button" aria-disabled="true">Sebelumnya</span>';for(let g=1;g<=t;g++)g===l?v+=`<span class="pager-button is-active" aria-current="page">${g}</span>`:v+=`<button type="button" class="pager-button" data-page="${g}">${g}</button>`;l<t?v+=`<button type="button" class="pager-button" data-page="${l+1}">Berikutnya</button>`:v+='<span class="pager-button" aria-disabled="true">Berikutnya</span>',h.innerHTML=v,h.querySelectorAll("button[data-page]").forEach(g=>{g.addEventListener("click",()=>{const x=Number(g.dataset.page)||1;d(k,x),c&&c.scrollIntoView({behavior:"smooth",block:"start"})})})};const n=M(document.querySelector("#event-modal"),{onClose:function(){document.body.classList.remove("modal-lock"),document.documentElement.classList.remove("modal-lock");const e=document.querySelector("#back-to-top");e&&e.style.removeProperty("display")}});o(q);const i=document.querySelector("#event-search"),u=document.querySelector("#event-search-clear"),f=document.querySelector("#event-filter-form"),p=document.querySelector("#event-count"),c=document.querySelector("#event-list"),h=document.querySelector("#event-pagination");let y=null,b=null,k=i?i.value.trim():"";async function d(e,r=1){k=e,u&&(u.style.display=e!==""?"inline-flex":"none");const t=new URL(window.location.href);e?t.searchParams.set("q",e):t.searchParams.delete("q"),r>1?t.searchParams.set("page",String(r)):t.searchParams.delete("page"),window.history.replaceState(null,"",t.pathname+(t.search?t.search:"")),b&&b.abort(),b=new AbortController,c&&(c.style.opacity="0.55",c.style.transition="opacity 150ms ease");try{const l=new URLSearchParams;e&&l.set("q",e),r>1&&l.set("page",String(r));const v="/event"+(l.toString()?`?${l.toString()}`:""),g=await fetch(v,{headers:{Accept:"application/json"},signal:b.signal});if(!g.ok)throw new Error("Search failed");const x=await g.json(),w=x.data||[],m=x.meta||{total:w.length,page:1,per_page:9,total_pages:1};if(p)if(m.total>0){const L=(m.page-1)*m.per_page+1,T=Math.min(m.page*m.per_page,m.total);p.innerHTML=`Menampilkan <span class="font-semibold text-neutral-900">${L}\u2013${T}</span> dari <span class="font-semibold text-neutral-900">${m.total}</span> event.`}else e?p.innerHTML=`Tidak ada event yang cocok dengan "<span class="font-semibold text-neutral-900">${a(e)}</span>".`:p.textContent="Belum ada event yang tersedia.";c&&(w.length>0?(c.innerHTML=w.map(L=>S(L)).join(""),o(c)):c.innerHTML='<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">Belum ada event yang cocok.</div>'),s(m,e)}catch(l){if(l.name==="AbortError")return;console.warn("Gagal memuat event live:",l)}finally{c&&(c.style.opacity="1")}}i&&(i.addEventListener("input",e=>{clearTimeout(y);const r=e.target.value;y=setTimeout(()=>{d(r.trim(),1)},250)}),i.addEventListener("keydown",e=>{e.key==="Enter"&&(e.preventDefault(),clearTimeout(y),d(i.value.trim(),1))})),u&&u.addEventListener("click",e=>{e.preventDefault(),i&&(i.value="",i.focus()),clearTimeout(y),d("",1)}),f&&f.addEventListener("submit",e=>{e.preventDefault(),clearTimeout(y),d(i?i.value.trim():"",1)}),h&&h.querySelectorAll("a.pager-button").forEach(e=>{e.addEventListener("click",r=>{try{const t=new URL(e.href,window.location.origin),l=Number(t.searchParams.get("page"))||1,v=t.searchParams.get("q")??k;r.preventDefault(),d(v,l),c&&c.scrollIntoView({behavior:"smooth",block:"start"})}catch{}})})}else{let y=function(){const s=o.query.toLowerCase().trim();return n.filter(d=>{const e=`${d.title} ${d.excerpt} ${d.location} ${d.status}`.toLowerCase();return!s||e.includes(s)})},b=function(){const s=y(),d=Math.max(1,Math.ceil(s.length/o.perPage));o.page=Math.min(o.page,d);const e=(o.page-1)*o.perPage,r=s.slice(e,e+o.perPage);u&&(u.textContent=s.length?`Menampilkan ${e+1}-${Math.min(e+o.perPage,s.length)} dari ${s.length} event.`:"Tidak ada event yang cocok."),f&&(f.innerHTML=r.length?r.map(t=>{const l=a(t.start_date||"")+(t.end_date&&t.end_date!==t.start_date?" \u2013 "+a(t.end_date):"");return`
        <article class="event-card">
          <div class="event-card-image">
            <img src="${B(t.image)}" alt="${a(t.title)}" loading="lazy" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
            <span class="event-card-badge ${t.status==="Upcoming"?"upcoming":""}">${a(t.status)}</span>
          </div>
          <div class="event-card-body">
            <div class="event-date-row">
              <svg class="h-3.5 w-3.5 text-[#114b9a] flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                <line x1="16" x2="16" y1="2" y2="6" />
                <line x1="8" x2="8" y1="2" y2="6" />
                <line x1="3" x2="21" y1="10" y2="10" />
              </svg>
              <span>${l}</span>
            </div>
            <h3 class="event-card-title">${a(t.title)}</h3>
            <p class="event-card-excerpt">${a(t.excerpt)}</p>
            <div class="event-card-location">
              ${t.location?`
                <svg class="h-3.5 w-3.5 flex-shrink-0 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                </svg>
                <span class="truncate">${a(t.location)}</span>
              `:""}
            </div>
          </div>
          <div class="event-card-footer">
            <button class="btn-event-detail open-event" data-id="${t.id}" data-slug="${t.slug||""}" aria-label="Lihat detail event ${a(t.title)}">
              <span>Lihat Detail Agenda</span>
              <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0-7.5 7.5M21 12H3" />
              </svg>
            </button>
          </div>
        </article>
      `}).join(""):'<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600">Belum ada event yang cocok.</div>',f.querySelectorAll(".open-event").forEach(t=>{t.addEventListener("click",()=>S({id:Number(t.dataset.id),slug:t.dataset.slug||""},c))})),k(d)},k=function(s){if(!p)return;if(s<=1){p.innerHTML="";return}const d=Array.from({length:s},(e,r)=>r+1);p.innerHTML=`
      <button class="pager-button" type="button" data-page="${Math.max(1,o.page-1)}" ${o.page===1?"disabled":""}>Sebelumnya</button>
      ${d.map(e=>`<button class="pager-button ${e===o.page?"is-active":""}" type="button" data-page="${e}">${e}</button>`).join("")}
      <button class="pager-button" type="button" data-page="${Math.min(s,o.page+1)}" ${o.page===s?"disabled":""}>Berikutnya</button>
    `,p.querySelectorAll("[data-page]").forEach(e=>{e.addEventListener("click",()=>{o.page=Number(e.dataset.page)||1,b(),f&&f.scrollIntoView({behavior:"smooth",block:"start"})})})},S=function(s,d){const e=n.find(r=>r.id===s.id||r.slug===s.slug);e&&j(e,d)};E();let n=[],o={query:"",page:1,perPage:9};const i=document.querySelector("#event-search"),u=document.querySelector("#event-count"),f=document.querySelector("#event-list"),p=document.querySelector("#event-pagination"),c=M(document.querySelector("#event-modal"),{onClose:function(){document.body.classList.remove("modal-lock"),document.documentElement.classList.remove("modal-lock");const s=document.querySelector("#back-to-top");s&&s.style.removeProperty("display")}});h();async function h(){u&&(u.textContent="Memuat data event..."),n=await I.getEventList(),i&&i.addEventListener("input",s=>{o.query=s.target.value,o.page=1,b()}),document.querySelector("#event-search-clear")?.addEventListener("click",()=>{i&&(i.value=""),o.query="",o.page=1,b()}),b()}}function $(n){return`/event/${encodeURIComponent(n.slug||n.id||"")}`}async function _(n,o){const i=document.querySelector("#back-to-top");i&&i.style.setProperty("display","none","important");try{const u=await fetch($(n),{headers:{Accept:"application/json"}});if(!u.ok){window.location.href=$(n);return}const p=(await u.json()).data;if(!p){window.location.href=$(n);return}j(p,o)}catch{window.location.href=$(n)}}function j(n,o){const i=window.GenBIUI?.safeImage||(h=>h||"https://genbijambi.com/public/uploads/slider-1.png"),u=`/event/${encodeURIComponent(n.slug||n.id||"")}`,f=a(n.start_date||"")+(n.end_date&&n.end_date!==n.start_date?" \u2013 "+a(n.end_date):""),p=a(n.excerpt||""),c=document.querySelector("#back-to-top");c&&c.style.setProperty("display","none","important"),o.open({content:`
    <div class="public-modal-panel event-detail-panel modal-panel is-open" role="dialog" aria-modal="true" aria-labelledby="event-modal-title">
      <button type="button" class="event-detail-close modal-close" data-modal-close aria-label="Tutup detail event" title="Tutup">
        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>

      <figure class="event-detail-image">
        <img src="${i(n.banner||n.image)}" alt="${a(n.title)}" onerror="this.src='https://genbijambi.com/public/uploads/slider-1.png'" />
        <span class="event-detail-badge ${n.status==="Upcoming"?"upcoming":""}">${a(n.status||"Event")}</span>
      </figure>

      <div class="event-detail-content">
        <div>
          <div class="flex flex-wrap items-center gap-2 pr-10">
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-[#114b9a] border border-blue-200/60">
              ${a(n.status||"Event")}
            </span>
          </div>
          <h2 id="event-modal-title" class="serif mt-3 text-2xl font-bold leading-tight tracking-tight text-neutral-950 md:text-3xl">
            ${a(n.title)}
          </h2>
        </div>

        <div class="event-info-cards" style="margin-top:1rem;">
          <div class="event-info-card">
            <div class="event-info-icon">
              <svg class="h-4 w-4 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <rect width="18" height="18" x="3" y="4" rx="2" ry="2" />
                <line x1="16" x2="16" y1="2" y2="6" />
                <line x1="8" x2="8" y1="2" y2="6" />
                <line x1="3" x2="21" y1="10" y2="10" />
              </svg>
            </div>
            <div>
              <span class="event-info-label">Tanggal Kegiatan</span>
              <span class="event-info-val">${f}</span>
            </div>
          </div>
          ${n.location?`
          <div class="event-info-card">
            <div class="event-info-icon">
              <svg class="h-4 w-4 text-[#114b9a]" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
              </svg>
            </div>
            <div>
              <span class="event-info-label">Lokasi</span>
              <span class="event-info-val">${a(n.location)}</span>
            </div>
          </div>
          `:""}
        </div>

        ${p?`
        <div class="event-modal-body" style="margin-top:1rem;">
          <div class="event-modal-body-header">Deskripsi Kegiatan</div>
          <p class="event-modal-body-text">${p}</p>
        </div>
        `:""}

        <div class="event-modal-footer" style="margin-top:auto;">
          <a data-transition href="${u}" class="btn btn-primary inline-flex items-center justify-center gap-2 w-full sm:w-auto" aria-label="Lihat detail lengkap ${a(n.title)}">
            <span>Baca Detail Lengkap</span>
            <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true">
              <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
          </a>
        </div>
      </div>
    </div>
  `})}})();
