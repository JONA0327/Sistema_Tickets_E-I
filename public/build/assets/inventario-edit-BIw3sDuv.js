document.addEventListener("DOMContentLoaded",()=>{const n=document.querySelector("[data-inventory-edit]");if(!n)return;const l=n.querySelector("#nuevas_imagenes"),c=n.querySelector("#image_preview"),o=n.querySelector("#image_count");l&&c&&o&&l.addEventListener("change",i=>{const t=Array.from(i.target.files||[]);c.innerHTML="";const d=["image/jpeg","image/jpg","image/png"],p=t.filter(e=>!d.includes(e.type));if(p.length>0){alert(`❌ Solo se permiten archivos JPG, JPEG y PNG.

Archivos no válidos detectados:
${p.map(e=>e.name).join(`
`)}`),l.value="",o.textContent="",o.className="text-xs text-green-600 font-medium";return}t.length>0?(o.textContent=`📸 ${t.length} nueva${t.length>1?"s":""} imagen${t.length>1?"es":""} seleccionada${t.length>1?"s":""}`,o.className="text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded"):(o.textContent="",o.className="text-xs text-green-600 font-medium"),t.forEach((e,a)=>{if(e.type.startsWith("image/")){const r=new FileReader;r.onload=s=>{const g=document.createElement("div");g.className="relative border rounded-lg overflow-hidden bg-blue-50",g.innerHTML=`
                            <img src="${s.target?.result||""}" class="w-full h-20 object-cover" />
                            <div class="text-xs text-center p-1 bg-blue-100">
                                <span class="font-medium text-blue-700">Nuevo #${a+1}</span>
                            </div>
                            <div class="text-xs text-gray-600 truncate px-1" title="${e.name}">${e.name}</div>
                        `,c.appendChild(g)},r.readAsDataURL(e)}})}),n.querySelectorAll("[data-delete-image]").forEach(i=>{i.addEventListener("click",()=>{const t=i.dataset.inventoryId,d=i.dataset.imageIndex;if(!t||typeof d>"u"||!window.confirm("¿Estás seguro de eliminar esta imagen?"))return;const e=document.createElement("form");e.method="POST",e.action=`/inventario/${t}/eliminar-imagen`;const a=document.createElement("input");a.type="hidden",a.name="_token",a.value=document.querySelector('meta[name="csrf-token"]')?.getAttribute("content")||n.querySelector('input[name="_token"]')?.value||"";const r=document.createElement("input");r.type="hidden",r.name="_method",r.value="DELETE";const s=document.createElement("input");s.type="hidden",s.name="indice",s.value=d,e.appendChild(a),e.appendChild(r),e.appendChild(s),document.body.appendChild(e),e.submit()})});const m=n.querySelector("form");if(!m)return;const u=m.querySelector('button[type="submit"]');m.addEventListener("submit",i=>{const t=Array.from(l?.files||[]);if(t.length>5&&!window.confirm(`Estás a punto de subir ${t.length} nuevas imágenes. Esto puede tardar un momento. ¿Continuar?`)){i.preventDefault();return}u&&t.length>0&&(u.innerHTML=`
                <svg class="w-4 h-4 mr-2 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Actualizando...
            `,u.disabled=!0)})});
