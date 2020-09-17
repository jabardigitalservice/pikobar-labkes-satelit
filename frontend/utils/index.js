/**
 * Get cookie from request.
 *
 * @param  {Object} req
 * @param  {String} key
 * @return {String|undefined}
 */
import moment from 'moment';

export function cookieFromRequest(req, key) {
  if (!req.headers.cookie) {
    return
  }

  const cookie = req.headers.cookie.split(';').find(
    c => c.trim().startsWith(`${key}=`)
  )

  if (cookie) {
    return cookie.split('=')[1]
  }
}

/**
 * https://router.vuejs.org/en/advanced/scroll-behavior.html
 */
export function scrollBehavior(to, from, savedPosition) {
  if (savedPosition) {
    return savedPosition
  }

  let position = {}

  if (to.matched.length < 2) {
    position = {
      x: 0,
      y: 0
    }
  } else if (to.matched.some(r => r.components.default.options.scrollToTop)) {
    position = {
      x: 0,
      y: 0
    }
  }
  if (to.hash) {
    position = {
      selector: to.hash
    }
  }

  return position
}

// function to compare or search input with data array
export function toLowerStartsWith(input, dataArray) {
  if (input.length < 1) {
    return []
  }
  return dataArray.filter(data => {
    return data.toLowerCase()
      .startsWith(input.toLowerCase())
  })
}

// function to check data falsy
export function checkDataFalsy(data) {
  const newData = data && data !== '0' ? data : null;
  return newData;
}

// function to check data input time is valid
export function isFormatTimeValid(time) {
  return moment(time, "HH:mm", true).isValid();
}

// function to get swal attribute for time validation
export function getAlertTimeMessage(desc) {
  const alertMessage = {
    title: "Format Waktu Salah!",
    text: `Mohon input ${desc} dengan format 'HH:mm' dimana HH(jam) 00-23 dan mm(menit) 00-59`,
    confirmButtonText: 'OK',
    icon: "warning",
  };
  return alertMessage;
}

export function getAlertDelete() {
  const alertMessage = {
    title: "Apakah anda yakin?",
    text: "Anda tidak dapat mengembalikan data yang telah di hapus!",
    showCancelButton: true,
    cancelButtonColor: '#d33',
    confirmButtonText: 'Ya, hapus!',
    cancelButtonText: 'Batal',
    icon: "warning",
  };
  return alertMessage;
}

export function humanize(str) {
  var i, frags = str.split('_');
  for (i = 0; i < frags.length; i++) {
    frags[i] = frags[i].charAt(0).toUpperCase() + frags[i].slice(1);
  }
  return frags.join(' ');
}

// function to get human age
export function getHumanAge(birthdDate, category) {
  const years = moment().diff(birthdDate, 'years');
  const months = moment().diff(birthdDate, 'months') % 12;
  if (category && category == 'tahun') {
    return `${years} tahun`;
  }
  return `${years} tahun ${months} bulan`;
}

// function to convert date use moment js
export function momentFormatDate(date) {
  return moment(date).lang("id").format("D MMMM YYYY");
}

// function to convert time use moment js
export function momentFormatTime(time) {
  return moment(time).format("HH:mm:ss");
}