<script>

document.addEventListener('DOMContentLoaded', function(){

window.openAreaTab = function(evt, area){

document.querySelectorAll('.area-content')
.forEach(x=>x.classList.remove('active'));

document.querySelectorAll('.tab-btn')
.forEach(x=>x.classList.remove('active'));

document.getElementById(area)?.classList.add('active');

evt.currentTarget.classList.add('active');
}


window.openSubTab = function(evt, tab){

const parent = evt.target.closest('.area-content');

parent.querySelectorAll('.sub-tab-content')
.forEach(x=>x.classList.remove('active'));

parent.querySelectorAll('.sub-tab-btn')
.forEach(x=>x.classList.remove('active'));

document.getElementById(tab)?.classList.add('active');

evt.currentTarget.classList.add('active');
}

});
</script>
<?php /**PATH /Users/lpmnudiymacpro/Documents/nuist/resources/views/talenta/partials/scripts.blade.php ENDPATH**/ ?>