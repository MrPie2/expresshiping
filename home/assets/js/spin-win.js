(function(){
'use strict';
const scriptUrl=document.currentScript ? document.currentScript.src : window.location.href;
const apiUrl=new URL('../../spin-api.php',scriptUrl).toString();
const overlay=document.getElementById('ehSpinOverlay');
if(!overlay)return;
const wheel=document.getElementById('ehSpinWheel');
const spinBtn=document.getElementById('ehSpinButton');
const claimForm=document.getElementById('ehSpinClaim');
const phoneInput=document.getElementById('ehSpinPhone');
const message=document.getElementById('ehSpinMessage');
const result=document.getElementById('ehSpinResult');
const prizeText=document.getElementById('ehSpinPrize');
const couponText=document.getElementById('ehSpinCoupon');
const colors=['#153c60','#e8a33d','#1d7180','#f1c46f','#244d70','#d8892b','#2b8290','#f5d28c'];
let spinToken=null, spinning=false;
function say(text){message.textContent=text;}
function drawWheel(segments){
 const ctx=wheel.getContext('2d'),size=wheel.width,center=size/2,radius=center-5;
 ctx.clearRect(0,0,size,size);
 const slice=Math.PI*2/segments.length;
 segments.forEach((segment,i)=>{
   const start=-Math.PI/2+i*slice,end=start+slice;
   ctx.beginPath();ctx.moveTo(center,center);ctx.arc(center,center,radius,start,end);ctx.closePath();
   ctx.fillStyle=colors[i%colors.length];ctx.fill();
   ctx.strokeStyle='#fff';ctx.lineWidth=3;ctx.stroke();
   ctx.save();ctx.translate(center,center);ctx.rotate(start+slice/2);
   ctx.textAlign='right';ctx.textBaseline='middle';ctx.fillStyle=(i%4===1||i%4===3)?'#17334d':'#fff';
   ctx.font='bold 23px Arial';let label=String(segment.name||'Prize');if(label.length>17)label=label.slice(0,15)+'…';
   ctx.fillText(label,radius-22,0);ctx.restore();
 });
 }
drawWheel([{name:'5% OFF'},{name:'10% OFF'},{name:'15% OFF'},{name:'FREE SHIPPING'}]);
async function request(data){
 let response;
 try {
  response=await fetch(apiUrl,{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},credentials:'same-origin',body:JSON.stringify(data)});
 } catch(networkError) {
  throw new Error('Could not reach the Spin & Win server. Check your connection and try again.');
 }
 const raw=await response.text();
 let payload;
 try { payload=raw ? JSON.parse(raw) : null; }
 catch(parseError) {
  const detail=raw.replace(/<script[^>]*>[\\s\\S]*?<\\/script>/gi,' ').replace(/<style[^>]*>[\\s\\S]*?<\\/style>/gi,' ').replace(/<[^>]+>/g,' ').replace(/\\s+/g,' ').trim().slice(0,180);
  throw new Error('The server returned an invalid response (HTTP '+response.status+'). '+(detail||'The response was empty. Check the PHP error log and database connection.'));
 }
 if(!payload||typeof payload!=='object') throw new Error('The server returned an empty response (HTTP '+response.status+'). Check the PHP error log and database connection.');
 if(!response.ok||!payload.ok) throw new Error(payload.message||'Spin request failed (HTTP '+response.status+').');
 return payload;
}
function close(){overlay.classList.remove('is-open');document.body.style.overflow='';}
document.getElementById('ehSpinClose').addEventListener('click',close);
overlay.addEventListener('click',e=>{if(e.target===overlay)close();});
function open(){if(sessionStorage.getItem('ehSpinDismissed')==='1'||sessionStorage.getItem('ehSpinPlayed')==='1')return;overlay.classList.add('is-open');document.body.style.overflow='hidden';}
setTimeout(open,1800);
document.getElementById('ehSpinClose').addEventListener('click',()=>sessionStorage.setItem('ehSpinDismissed','1'));
spinBtn.addEventListener('click',async()=>{
 if(spinning)return;spinning=true;spinBtn.disabled=true;spinBtn.textContent='Spinning…';say('Good luck! Your prize is being selected.');
 try{
  const data=await request({action:'spin'});spinToken=data.token;drawWheel(data.segments);
  const slice=360/data.segments.length;const target=1800-(data.selected_index+.5)*slice;
  wheel.style.transform='rotate('+target+'deg)';
  wheel.addEventListener('transitionend',function done(){
   wheel.removeEventListener('transitionend',done);prizeText.textContent=data.reward.name;
   claimForm.classList.add('is-visible');spinBtn.style.display='none';
   say('You won! Enter your phone number to receive your coupon code.');
  });
 }catch(error){say(error.message);spinBtn.disabled=false;spinBtn.textContent='Spin the wheel';spinning=false;}
});
claimForm.addEventListener('submit',async e=>{
 e.preventDefault();const phone=phoneInput.value.trim();if(!phone){say('Please enter your phone number.');return;}
 const submit=claimForm.querySelector('button');submit.disabled=true;submit.textContent='Generating coupon…';
 try{
  const data=await request({action:'claim',token:spinToken,phone});
  couponText.textContent=data.coupon.coupon_code;result.classList.add('is-visible');
  prizeText.textContent=data.coupon.reward_name;sessionStorage.setItem('ehSpinPlayed','1');
  say('Save this coupon code and present it to Expresshipping when placing your order.');
  claimForm.style.display='none';
 }catch(error){say(error.message);submit.disabled=false;submit.textContent='Claim my coupon';}
});
})();