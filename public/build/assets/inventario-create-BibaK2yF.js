document.addEventListener("DOMContentLoaded",()=>{const r=document.querySelector("[data-inventory-create]");if(!r)return;const o=r.querySelector("#imagenes"),s=r.querySelector("#image_preview"),n=r.querySelector("#image_count");o&&s&&n&&o.addEventListener("change",l=>{const e=Array.from(l.target.files||[]);s.innerHTML="";const c=["image/jpeg","image/jpg","image/png"],m=e.filter(t=>!c.includes(t.type));if(m.length>0){alert(`❌ Solo se permiten archivos JPG, JPEG y PNG.

Archivos no válidos detectados:
${m.map(t=>t.name).join(`
`)}`),o.value="",n.textContent="",n.className="text-xs text-green-600 font-medium";return}e.length>0?(n.textContent=`📸 ${e.length} imagen${e.length>1?"es":""} seleccionada${e.length>1?"s":""}`,n.className="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded"):(n.textContent="",n.className="text-xs text-green-600 font-medium"),e.forEach((t,g)=>{if(t.type.startsWith("image/")){const u=new FileReader;u.onload=p=>{const d=document.createElement("div");d.className="relative border rounded-lg overflow-hidden bg-green-50",d.innerHTML=`
                            <img src="${p.target?.result||""}" class="w-full h-20 object-cover" />
                            <div class="text-xs text-center p-1 bg-green-100">
                                <span class="font-medium text-green-700">#${g+1}</span>
                            </div>
                            <div class="text-xs text-gray-600 truncate px-1" title="${t.name}">${t.name}</div>
                        `,s.appendChild(d)},u.readAsDataURL(t)}})});const i=r.querySelector("form");if(!i)return;const a=i.querySelector('button[type="submit"]');i.addEventListener("submit",l=>{const e=Array.from(o?.files||[]);if(e.length>5&&!window.confirm(`Estás a punto de subir ${e.length} imágenes. Esto puede tardar un momento. ¿Continuar?`)){l.preventDefault();return}a&&e.length>0&&(a.innerHTML=`
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Procesando...
            `,a.disabled=!0)})});
