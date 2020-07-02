import Vue from 'vue'
import Breadcrumbs from '~/components/Breadcrumbs'

Vue.component(Breadcrumbs.name, Breadcrumbs)

Vue.filter('formatCurrency', function (value, c, d, t) {
var n = value, 
    c = isNaN(c = Math.abs(c)) ? 0 : c, 
    d = d == undefined ? "," : d, 
    t = t == undefined ? "." : t, 
    s = n < 0 ? "-" : "", 
    i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", 
    j = (j = i.length) > 3 ? j % 3 : 0;
  if (value == '-') return '-'
   return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
});
Vue.filter('formatDate', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
  var tanggal = parseInt(value.toString().substring(8,10));
  var bulan = parseInt(value.toString().substring(5,7));
  return tanggal + " " +nmBulan[bulan]+" "+value.toString().substring(0,4)
})
Vue.filter('formatDateTime', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    var jam = date.getHours()
    var menit = date.getMinutes()
    // return nmBulan[bulan];
    return tanggal + " " +nmBulan[bulan]+" "+date.getFullYear() + " " + jam + ":" + menit;
  }
  var tanggal = parseInt(value.toString().substring(8,10));
  var bulan = parseInt(value.toString().substring(5,7));
  var jam = value.toString().substring(11,13)
  var menit = value.toString().substring(14,16)
  // return nmBulan[bulan];
  return tanggal + " " +nmBulan[bulan]+" "+value.toString().substring(0,4) + " " + jam + ":" + menit;
});
Vue.filter('formatTime', function (value) {
  if (!value || value == '') return ''
  var nmBulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
  if (value.indexOf('T') > -1) {
    var date = new Date(value)
    var tanggal = date.getDate();
    var bulan = date.getMonth() + 1;
    var jam = date.getHours()
    var menit = date.getMinutes()
    // return nmBulan[bulan];
    return jam + ":" + menit;
  }
  var tanggal = parseInt(value.toString().substring(8,10));
  var bulan = parseInt(value.toString().substring(5,7));
  var jam = value.toString().substring(11,13)
  var menit = value.toString().substring(14,16)
  // return nmBulan[bulan];
  return jam + ":" + menit;
});
