<?php
session_start();
if (empty($_SESSION['User_id'])) { header('Location: login.php'); exit; }
if (empty($_SESSION['spin_admin_csrf'])) $_SESSION['spin_admin_csrf'] = bin2hex(random_bytes(32));
$spinCsrf = $_SESSION['spin_admin_csrf'];
include('bootstrap.php');
include('Header.php');
?>
<style>
.spin-admin{max-width:1180px;margin:34px auto 70px}.spin-admin .panel{border:1px solid #e5ebf1;border-radius:14px;background:#fff;box-shadow:0 8px 24px #14283d0a;padding:22px;margin-bottom:22px}.spin-admin h1{font-weight:800;color:#173653}.spin-admin .muted{color:#718096}.spin-admin .table td,.spin-admin .table th{vertical-align:middle}.spin-admin .status{font-size:11px;text-transform:uppercase;font-weight:800;letter-spacing:.5px}.spin-admin .form-label{font-weight:650}
</style>
<div class="container spin-admin">
 <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
  <div><div class="text-uppercase small fw-bold text-warning">Promotion management</div><h1 class="h2 mb-1">Spin &amp; Win</h1><div class="muted">Manage prizes, winning odds and coupon redemption.</div></div>
  <a class="btn btn-outline-secondary" href="Index.php">Back to admin</a>
 </div>
 <div id="spinAdminNotice" class="alert d-none" role="status"></div>
 <div class="panel">
  <h2 class="h5 mb-3" id="spinRewardFormTitle">Add a prize</h2>
  <form id="spinRewardForm" class="row g-3">
   <input type="hidden" name="id" value="0">
   <div class="col-md-4"><label class="form-label">Prize label</label><input class="form-control" name="name" maxlength="120" placeholder="e.g. 10% OFF" required></div>
   <div class="col-md-2"><label class="form-label">Reward type</label><select class="form-select" name="reward_type"><option value="percent">Percentage</option><option value="fixed">Fixed amount</option><option value="free_shipping">Free shipping</option><option value="other">Other</option></select></div>
   <div class="col-md-2"><label class="form-label">Value</label><input class="form-control" type="number" min="0" step="0.01" name="reward_value" value="0"></div>
   <div class="col-md-2"><label class="form-label">Probability weight</label><input class="form-control" type="number" min="0" step="0.001" name="probability" value="10" required></div>
   <div class="col-md-2"><label class="form-label">Max coupons</label><input class="form-control" type="number" min="1" name="max_coupons" placeholder="Unlimited"></div>
   <div class="col-md-2"><label class="form-label">Expiry (days)</label><input class="form-control" type="number" min="1" max="3650" name="expiry_days" value="30" required></div>
   <div class="col-md-2 d-flex align-items-end"><div class="form-check mb-2"><input class="form-check-input" type="checkbox" name="is_active" checked><label class="form-check-label">Active prize</label></div></div>
   <div class="col-md-8 d-flex align-items-end gap-2"><button class="btn btn-primary" type="submit">Save prize</button><button class="btn btn-light" type="button" id="spinRewardReset">Clear form</button></div>
  </form>
 </div>
 <div class="panel">
  <div class="d-flex align-items-center justify-content-between mb-3"><h2 class="h5 mb-0">Prize inventory</h2><button class="btn btn-sm btn-outline-primary" id="spinRefresh">Refresh</button></div>
  <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Prize</th><th>Type / Value</th><th>Weight</th><th>Issued / Limit</th><th>Expiry</th><th>Status</th><th></th></tr></thead><tbody id="spinRewardsRows"><tr><td colspan="7">Loading…</td></tr></tbody></table></div>
 </div>
 <div class="panel">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3"><h2 class="h5 mb-0">Coupons &amp; winners</h2><form id="spinCouponSearch" class="d-flex gap-2"><input class="form-control form-control-sm" name="query" placeholder="Search code or phone"><button class="btn btn-sm btn-outline-primary">Search</button></form></div>
  <div class="table-responsive"><table class="table table-hover"><thead><tr><th>Coupon</th><th>Prize</th><th>Phone</th><th>Issued</th><th>Expires</th><th>Status</th><th>Action</th></tr></thead><tbody id="spinCouponsRows"><tr><td colspan="7">Loading…</td></tr></tbody></table></div>
  <div class="small muted">Showing up to 200 recent coupons by default. Search by coupon code or phone number to verify a specific winner.</div>
 </div>
</div>
<script>
(function(){
const csrf=<?php echo json_encode($spinCsrf); ?>, api='../home/spin-api.php';
const notice=document.getElementById('spinAdminNotice'),form=document.getElementById('spinRewardForm');
function esc(v){return String(v??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
function tell(msg,ok=true){notice.textContent=msg;notice.className='alert '+(ok?'alert-success':'alert-danger');}
async function call(action,extra={}){const r=await fetch(api,{method:'POST',headers:{'Content-Type':'application/json'},credentials:'same-origin',body:JSON.stringify({action,csrf,...extra})});const d=await r.json();if(!r.ok||!d.ok)throw new Error(d.message||'Request failed');return d;}
function rewardRows(rows){document.getElementById('spinRewardsRows').innerHTML=rows.length?rows.map(x=>'<tr><td class="fw-bold">'+esc(x.name)+'</td><td>'+esc(x.reward_type)+' / '+esc(x.reward_value)+'</td><td>'+esc(x.probability)+'</td><td>'+esc(x.issued_count)+' / '+(x.max_coupons===null?'∞':esc(x.max_coupons))+'</td><td>'+esc(x.expiry_days)+' days</td><td><span class="badge '+(Number(x.is_active)?'bg-success':'bg-secondary')+'">'+(Number(x.is_active)?'Active':'Paused')+'</span></td><td class="text-nowrap"><button class="btn btn-sm btn-outline-primary me-1" data-edit="'+esc(x.id)+'">Edit</button><button class="btn btn-sm btn-outline-danger" data-delete="'+esc(x.id)+'">Remove</button></td></tr>').join(''):'<tr><td colspan="7">No prizes configured.</td></tr>';document.getElementById('spinRewardsRows').dataset.rows=JSON.stringify(rows);}
function couponRows(rows){document.getElementById('spinCouponsRows').innerHTML=rows.length?rows.map(x=>'<tr><td class="fw-bold">'+esc(x.coupon_code)+'</td><td>'+esc(x.reward_name)+'</td><td>'+esc(x.phone||'Not claimed')+'</td><td>'+esc(x.spun_at)+'</td><td>'+esc(x.expires_at||'—')+'</td><td><span class="status">'+esc(x.status)+'</span></td><td>'+(x.status==='active'?'<button class="btn btn-sm btn-success" data-redeem="'+esc(x.coupon_code)+'">Mark redeemed</button>':'—')+'</td></tr>').join(''):'<tr><td colspan="7">No coupons found.</td></tr>';}
async function load(){try{const d=await call('admin_list');rewardRows(d.rewards);couponRows(d.coupons);}catch(e){tell(e.message,false);}}
form.addEventListener('submit',async e=>{e.preventDefault();const fd=new FormData(form),data={};fd.forEach((v,k)=>data[k]=v);data.id=Number(data.id||0);data.reward_value=Number(data.reward_value||0);data.probability=Number(data.probability||0);data.expiry_days=Number(data.expiry_days||30);data.is_active=form.elements.is_active.checked?1:0;try{const d=await call('admin_save_reward',data);tell(d.message);form.reset();form.elements.id.value=0;form.elements.is_active.checked=true;document.getElementById('spinRewardFormTitle').textContent='Add a prize';load();}catch(e){tell(e.message,false);}});
document.getElementById('spinRewardReset').addEventListener('click',()=>{form.reset();form.elements.id.value=0;form.elements.is_active.checked=true;document.getElementById('spinRewardFormTitle').textContent='Add a prize';});
document.getElementById('spinRefresh').addEventListener('click',load);
document.getElementById('spinRewardsRows').addEventListener('click',async e=>{const edit=e.target.closest('[data-edit]'),del=e.target.closest('[data-delete]');const rows=JSON.parse(e.currentTarget.dataset.rows||'[]');if(edit){const x=rows.find(r=>Number(r.id)===Number(edit.dataset.edit));if(!x)return;['id','name','reward_type','reward_value','probability','max_coupons','expiry_days'].forEach(k=>form.elements[k].value=x[k]??'');form.elements.is_active.checked=Number(x.is_active)===1;document.getElementById('spinRewardFormTitle').textContent='Edit prize';form.scrollIntoView({behavior:'smooth'});}if(del&&confirm('Remove this prize? Existing coupons will remain in the records.')){try{const d=await call('admin_delete_reward',{id:Number(del.dataset.delete)});tell(d.message);load();}catch(err){tell(err.message,false);}}});
document.getElementById('spinCouponsRows').addEventListener('click',async e=>{const b=e.target.closest('[data-redeem]');if(!b||!confirm('Mark '+b.dataset.redeem+' as redeemed?'))return;try{const d=await call('admin_redeem',{code:b.dataset.redeem});tell(d.message,d.ok);load();}catch(err){tell(err.message,false);}});
document.getElementById('spinCouponSearch').addEventListener('submit',async e=>{e.preventDefault();const query=new FormData(e.currentTarget).get('query');try{const d=await call('admin_search_coupon',{query});couponRows(d.coupons);}catch(err){tell(err.message,false);}});
load();
})();
</script>
<?php include('Footer.php'); ?>
