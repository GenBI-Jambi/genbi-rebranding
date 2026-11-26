(function(){"use strict";const{formatInitials:f,renderShell:B}=window.GenBIApp,{createCustomSelect:w,createModalController:C,observeFadeUp:I,safeImage:$,unique:b}=window.GenBIUI,k=window.GenBIAPI;B("team"),I();const S=C(document.querySelector("#member-modal"));let p=Array.isArray(window.teamMembersData)?[...window.teamMembersData]:[],m={divisions:[],campuses:[],years:[]},e={query:"",division:"Semua",campus:"Semua",year:"Semua",page:1,perPage:12,layout:"grid"};function r(a){return a==null?"":String(a).replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;").replace(/"/g,"&quot;").replace(/'/g,"&#39;")}function M(a){if(!a)return;a.querySelectorAll(".team-public-card, .team-public-row").forEach(n=>{if(n.dataset.modalBound==="1")return;n.dataset.modalBound="1";const c=u=>{if(u.target.closest("a"))return;const d=Number(n.dataset.id);d&&E(d)};n.addEventListener("click",c),n.addEventListener("keydown",u=>{(u.key==="Enter"||u.key===" ")&&(u.preventDefault(),c(u))})})}function E(a){let o=null;if(Array.isArray(window.teamMembersData)&&(o=window.teamMembersData.find(n=>Number(n.id)===a)),!o&&Array.isArray(p)&&(o=p.find(n=>Number(n.id)===a)),o){L(o);return}k.getTeamList().then(n=>{n&&Array.isArray(n.members)&&(p=n.members,o=p.find(c=>Number(c.id)===a),o&&L(o))}).catch(()=>{})}function L(a){if(!S)return;const o=a.photo?`<img src="${$(a.photo)}" alt="${r(a.name)}" class="w-full h-full object-cover" style="object-position: center 20%;" onerror="this.parentElement.innerHTML='<span class=\\'text-3xl font-bold text-[#114b9a]\\'>${f(a.name)}</span>'" />`:`<span class="text-3xl font-bold text-[#114b9a]">${f(a.name)}</span>`,n=a.detail||a.bio?`<div class="mt-4 pt-3.5 border-t border-neutral-100">
        <p class="text-[11px] font-semibold uppercase tracking-wider text-neutral-400 mb-1">Catatan</p>
        <p class="text-xs text-neutral-600 leading-relaxed">${r(a.detail||a.bio)}</p>
       </div>`:"",c=[];if(a.instagram){const d=String(a.instagram).trim(),g=d.replace(/^@/,"").replace(/^(https?:\/\/)?(www\.)?instagram\.com\/?/,""),x=d.startsWith("http")?d:`https://instagram.com/${g}`;c.push(`<a href="${x}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="Instagram">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 1 0 0 12.324 6.162 6.162 0 0 0 0-12.324zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm6.406-11.845a1.44 1.44 0 1 0 0 2.881 1.44 1.44 0 0 0 0-2.881z"/></svg>
      <span>Instagram</span>
    </a>`)}if(a.linkedin){const d=String(a.linkedin).trim(),g=d.startsWith("http")?d:`https://linkedin.com/in/${d}`;c.push(`<a href="${g}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="LinkedIn">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
      <span>LinkedIn</span>
    </a>`)}if(a.email&&c.push(`<a href="mailto:${r(a.email)}" class="team-modal-link" title="Email">
      <svg class="h-3.5 w-3.5 fill-none stroke-current" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.24a2.25 2.25 0 01-1.07 1.92l-7.5 4.62a2.25 2.25 0 01-2.36 0l-7.5-4.62a2.25 2.25 0 01-1.07-1.92v-.24"/></svg>
      <span>Email</span>
    </a>`),a.phone){const d=String(a.phone).replace(/[^0-9]/g,""),g=d.startsWith("0")?"62"+d.slice(1):d;c.push(`<a href="https://wa.me/${g}" target="_blank" rel="noopener noreferrer" class="team-modal-link" title="WhatsApp">
      <svg class="h-3.5 w-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981z"/></svg>
      <span>WhatsApp</span>
    </a>`)}const u=c.length?`<div class="mt-4 pt-3.5 border-t border-neutral-100 flex flex-wrap gap-2">${c.join("")}</div>`:"";S.open({content:`
    <div class="team-detail-modal-panel relative w-full bg-white text-neutral-900" role="dialog" aria-modal="true" aria-labelledby="member-modal-title">
      <button class="modal-close absolute top-4 right-4 flex h-8 w-8 items-center justify-center rounded-full text-neutral-400 hover:text-neutral-900 hover:bg-neutral-100 transition-colors focus:outline-none" aria-label="Tutup detail profil">
        <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
      </button>
      <div class="flex flex-col sm:flex-row gap-5 sm:gap-6 items-start">
        <div class="team-modal-photo-col w-32 sm:w-40 flex-shrink-0 mx-auto sm:mx-0">
          <div class="team-modal-photo-box w-full aspect-[4/5] rounded-xl overflow-hidden bg-neutral-100 border border-neutral-200/60 shadow-sm flex items-center justify-center">
            ${o}
          </div>
        </div>
        <div class="team-modal-info-col min-w-0 flex-1 w-full">
          <div>
            <p class="text-[11px] font-bold uppercase tracking-wider text-[#114b9a]">GenBI Provinsi Jambi</p>
            <h2 id="member-modal-title" class="serif mt-1 text-xl sm:text-2xl font-bold tracking-tight text-neutral-950">${r(a.name)}</h2>
            <p class="mt-0.5 text-xs sm:text-sm font-semibold text-[#114b9a]">${r(a.role||"Anggota")}</p>
          </div>
          <div class="mt-4 team-credential-box">
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v1.069m7.5 0c.98.056 1.95.14 2.91.251m-13.32 0c.96-.112 1.93-.196 2.91-.252"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Divisi / Bidang</span>
                <span class="team-credential-val">${r(a.division||"-")}</span>
              </div>
            </div>
            <div class="team-credential-divider" aria-hidden="true"></div>
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.333M4.5 21V10.333"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Komisariat / Kampus</span>
                <span class="team-credential-val">${r(a.campus||"-")}</span>
              </div>
            </div>
            <div class="team-credential-divider" aria-hidden="true"></div>
            <div class="team-credential-item">
              <div class="team-credential-icon" aria-hidden="true">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
              </div>
              <div class="team-credential-text">
                <span class="team-credential-label">Periode Kepengurusan</span>
                <span class="team-credential-val">${r(a.year?"Tahun "+a.year:"-")}</span>
              </div>
            </div>
          </div>
          ${n}
          ${u}
        </div>
      </div>
    </div>
  `})}const y=document.querySelector('#team-list[data-ssr="true"]');if(y){document.body.classList.add("page-ready");const a=document.querySelector("#team-layout-grid"),o=document.querySelector("#team-layout-list");a?.addEventListener("click",()=>{y.className="fade-up team-public-grid in-view",a.classList.add("is-active"),o?.classList.remove("is-active")}),o?.addEventListener("click",()=>{y.className="fade-up team-public-list in-view",o.classList.add("is-active"),a?.classList.remove("is-active")}),M(y);const n=document.querySelector("#team-filter-form"),c=document.querySelector("#team-search");c&&n&&c.addEventListener("keydown",u=>{u.key==="Enter"&&(u.preventDefault(),n.submit())})}else{let x=function(t){e.layout=t,v(),q(),h()},q=function(){u?.classList.toggle("is-active",e.layout==="grid"),d?.classList.toggle("is-active",e.layout==="list")},T=function(){const t=new URLSearchParams(window.location.search),i=t.get("view")||t.get("layout");(i==="grid"||i==="list")&&(e.layout=i),e.query=t.get("q")||"",e.division=t.get("division")||"Semua",e.campus=t.get("campus")||"Semua",e.year=t.get("year")||"Semua",e.page=Math.max(1,Number(t.get("page"))||1)},v=function(){const t=new URLSearchParams;e.layout!=="grid"&&t.set("view",e.layout),e.query.trim()&&t.set("q",e.query.trim()),e.division!=="Semua"&&t.set("division",e.division),e.campus!=="Semua"&&t.set("campus",e.campus),e.year!=="Semua"&&t.set("year",e.year),e.page>1&&t.set("page",String(e.page));const i=t.toString(),s=i?`${window.location.pathname}?${i}`:window.location.pathname;window.history.replaceState({},"",s)},z=function(){const t=e.query.toLowerCase().trim();return p.filter(i=>{const s=`${i.name} ${i.role} ${i.division} ${i.campus} ${i.commission} ${i.year}`.toLowerCase();return(!t||s.includes(t))&&(e.division==="Semua"||i.division===e.division)&&(e.campus==="Semua"||i.campus===e.campus||i.commission===e.campus)&&(e.year==="Semua"||String(i.year)===String(e.year))})},h=function(){if(!n)return;const t=z(),i=Math.max(1,Math.ceil(t.length/e.perPage));e.page=Math.min(e.page,i),v();const s=(e.page-1)*e.perPage,l=t.slice(s,s+e.perPage);o&&(o.textContent=t.length?`Menampilkan ${s+1}\u2013${Math.min(s+e.perPage,t.length)} dari ${t.length} anggota (${p.length} total).`:"Belum ada data anggota yang cocok dengan filter."),n.className=`fade-up ${e.layout==="list"?"team-public-list":"team-public-grid"} in-view`,n.innerHTML=l.length?l.map(A=>e.layout==="list"?j(A):P(A)).join(""):'<div class="rounded-2xl border border-neutral-900/10 bg-white p-8 text-center text-sm text-neutral-600 col-span-full">Belum ada data anggota yang cocok dengan filter.</div>',M(n),D(i)},P=function(t){return`
      <article class="team-public-card cursor-pointer group" data-id="${t.id}" tabindex="0" role="button" aria-label="Lihat profil ${r(t.name)}" title="Klik untuk melihat profil lengkap">
        <div class="team-public-photo">
          ${t.photo?`<img src="${$(t.photo)}" alt="${r(t.name)}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${f(t.name)}'))" />`:`<span>${f(t.name)}</span>`}
        </div>
        <div>
          <p class="eyebrow">${r(t.year?"Tahun "+t.year:"GenBI Jambi")}</p>
          <h3 class="serif mt-2 text-2xl font-semibold tracking-tight text-neutral-950 group-hover:text-[#114b9a] transition-colors">${r(t.name)}</h3>
          <p class="mt-2 text-sm font-semibold text-blue-800">${r(t.role||"Anggota")}</p>
          <p class="mt-3 text-sm leading-6 text-neutral-600">${r(t.division||"-")}</p>
          <p class="text-sm leading-6 text-neutral-500">${r(t.campus||"-")}</p>
        </div>
      </article>
    `},j=function(t){return`
      <article class="team-public-row cursor-pointer group" data-id="${t.id}" tabindex="0" role="button" aria-label="Lihat profil ${r(t.name)}" title="Klik untuk melihat profil lengkap">
        <div class="team-public-row-photo">
          ${t.photo?`<img src="${$(t.photo)}" alt="${r(t.name)}" loading="lazy" onerror="this.replaceWith(document.createTextNode('${f(t.name)}'))" />`:`<span>${f(t.name)}</span>`}
        </div>
        <div>
          <h3 class="text-base font-bold text-neutral-950 group-hover:text-[#114b9a] transition-colors">${r(t.name)}</h3>
          <p class="mt-1 text-sm leading-6 text-neutral-600">${r(t.role||"Anggota")}</p>
        </div>
        <div class="text-sm leading-6 text-neutral-600">
          <p class="font-semibold text-neutral-800">${r(t.division||"-")}</p>
          <p>${r(t.campus||"-")}</p>
        </div>
        <div class="text-sm font-semibold text-neutral-500">${r(t.year?"Tahun "+t.year:"")}</div>
      </article>
    `},N=function(t,i){return i<=7?Array.from({length:i},(s,l)=>l+1):t<=4?[1,2,3,4,5,"...",i]:t>=i-3?[1,"...",i-4,i-3,i-2,i-1,i]:[1,"...",t-1,t,t+1,"...",i]},D=function(t){if(!c)return;if(t<=1){c.innerHTML="";return}const i=N(e.page,t);c.innerHTML=`
      <button class="pager-button" type="button" data-page="${Math.max(1,e.page-1)}" ${e.page===1?'disabled aria-disabled="true"':""}>Sebelumnya</button>
      ${i.map(s=>s==="..."?'<span class="pager-ellipsis" aria-hidden="true">&hellip;</span>':`<button class="pager-button ${s===e.page?"is-active":""}" type="button" data-page="${s}" ${s===e.page?'aria-current="page"':""}>${s}</button>`).join("")}
      <button class="pager-button" type="button" data-page="${Math.min(t,e.page+1)}" ${e.page===t?'disabled aria-disabled="true"':""}>Berikutnya</button>
    `,c.querySelectorAll("[data-page]").forEach(s=>{s.addEventListener("click",()=>{s.disabled||s.getAttribute("aria-disabled")==="true"||(e.page=Number(s.dataset.page)||1,v(),h(),n.scrollIntoView({behavior:"smooth",block:"start"}))})})};T();const a=document.querySelector("#team-search"),o=document.querySelector("#team-count"),n=document.querySelector("#team-list"),c=document.querySelector("#team-pagination"),u=document.querySelector("#team-layout-grid"),d=document.querySelector("#team-layout-list");g();async function g(){o&&(o.textContent="Memuat data anggota..."),a&&(a.value=e.query),q();try{const l=await k.getTeamList();p=l.members||[],m=l.filters||m}catch{p=[]}const t=document.querySelector("#team-division");t&&w(t,{label:"Divisi",options:b(m.divisions.length?m.divisions:p.map(l=>l.division)),value:e.division,onChange:l=>{e.division=l,e.page=1,v(),h()}});const i=document.querySelector("#team-campus");i&&w(i,{label:"Komisariat/Kampus",options:b(m.campuses.length?m.campuses:p.map(l=>l.campus)),value:e.campus,onChange:l=>{e.campus=l,e.page=1,v(),h()}});const s=document.querySelector("#team-year");s&&w(s,{label:"Tahun",options:b(m.years.length?m.years:p.map(l=>l.year)),value:e.year,onChange:l=>{e.year=l,e.page=1,v(),h()}}),a?.addEventListener("input",l=>{e.query=l.target.value,e.page=1,v(),h()}),u?.addEventListener("click",()=>x("grid")),d?.addEventListener("click",()=>x("list")),h()}}})();
