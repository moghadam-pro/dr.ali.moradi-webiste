(function () {
 'use strict';
 document.addEventListener('DOMContentLoaded', function () {
  var root = document.getElementById('dam-patient-gallery-editor');
  var data = document.getElementById('dam-patient-gallery-data');
  if (!root || !data) return;
  var items = JSON.parse(data.value || '[]');
  function save() { data.value = JSON.stringify(items); data.dispatchEvent(new Event('change', {bubbles: true})); }
  function field(row, item, key, label, tag) {
   var wrap = document.createElement('label'); wrap.style.display = 'block'; wrap.textContent = label;
   var input = document.createElement(tag || 'input'); input.value = item[key] || ''; input.style.width = '100%';
   input.addEventListener('input', function () { item[key] = input.value; save(); }); wrap.appendChild(input); row.appendChild(wrap);
  }
  function button(row, label, action) { var b = document.createElement('button'); b.type = 'button'; b.className = 'button'; b.textContent = label; b.addEventListener('click', action); row.appendChild(b); }
  function render() {
   root.replaceChildren();
   items.forEach(function (item, index) {
    var row = document.createElement('div'); row.style.cssText = 'border:1px solid #ddd;padding:12px;margin:12px 0';
    if (item.type === 'image') { var img = document.createElement('img'); img.src = item.url; img.style.cssText = 'max-width:160px;max-height:120px'; row.appendChild(img); }
    field(row, item, 'title', damPatientEditor.title);
    field(row, item, 'description', damPatientEditor.description, 'textarea');
    if (!item.id) field(row, item, 'url', damPatientEditor.url);
    button(row, damPatientEditor.up, function () { if(index > 0) { var previous = items[index-1]; items[index-1] = item; items[index] = previous; save(); render(); } });
    button(row, damPatientEditor.down, function () { if(index < items.length-1) { var next = items[index+1]; items[index+1] = item; items[index] = next; save(); render(); } });
    button(row, damPatientEditor.remove, function () { items.splice(index, 1); save(); render(); }); root.appendChild(row);
   });
  }
  document.getElementById('dam-patient-add-media').addEventListener('click', function () {
   var frame = wp.media({multiple: true, library: {type: ['image', 'video']}});
   frame.on('select', function () {
    frame.state().get('selection').toJSON().forEach(function (media) {
     items.push({id: media.id, url: media.url, type: media.type === 'video' ? 'video' : 'image', title: media.title || '', description: media.caption || ''});
    }); save(); render();
   }); frame.open();
  });
  document.getElementById('dam-patient-add-link').addEventListener('click', function () { items.push({id: 0, url: '', type: 'link', title: '', description: ''}); save(); render(); });
  render();
 });
})();
