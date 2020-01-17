/* eslint-disable no-undef */
// eslint-disable-next-line no-extend-native
Array.prototype.remove = function (value) {
  let idx = this.indexOf(value)
  if (idx !== -1) {
    return this.splice(idx, 1)
  }
  return false
}

export function getCookie (name) {
  let value = '; ' + document.cookie
  let parts = value.split('; ' + name + '=')
  return parts.length !== 2
    ? undefined : parts.pop().split(';').shift()
}

export function setCookie (name, value, expiryDays = 365, domain = '', path = '/') {
  let exdate = new Date()
  exdate.setDate(exdate.getDate() + (expiryDays || 365))

  let cookie = [
    name + '=' + value,
    'expires=' + exdate.toUTCString(),
    'path=' + (path || '/')
  ]

  if (domain) {
    cookie.push('domain=' + domain)
  }
  document.cookie = cookie.join(';')

  console.log(cookie)
}

export function slugify (text) {
  return text.toString().toLowerCase().trim()
    .replace(/[^\w\s-]/g, '') // remove non-word [a-z0-9_], non-whitespace, non-hyphen characters
    .replace(/[\s_-]+/g, '_') // swap any length of whitespace, underscore, hyphen characters with a single _
    .replace(/^-+|-+$/g, '') // remove leading, trailing -
}

export function cleanArray (actual) {
  let newArray = []
  for (let i = 0; i < actual.length; i++) {
    if (actual[i]) newArray.push(actual[i])
  }
  return newArray.pop()
}

export function addLoadEvent (func) {
  let oldonload = window.onload
  if (typeof window.onload !== 'function') {
    window.onload = func
  } else {
    window.onload = () => {
      if (oldonload) {
        oldonload()
      }
      func()
    }
  }
}

/**
 * Make all the links in text clickable
 * @param selector Actual selector of the text
 */
export function autoLink (selector) {
  let autolinker = new Autolinker({
    newWindow: true,
    truncate: 25
  })

  $(selector).each(function () {
    $(this).html(autolinker.link($(this).html()))
  })
}

export function countItUp () {
  const options = {
    useEasing: true,
    useGrouping: true,
    separator: ',',
    decimal: '.'
  }

  let speed = $(this).data('speed') || (Math.random() * (5 - 3) + 3)

  $('.countItUp').each(function () {
    let countItUp = new CountUp(this, 0, $(this).data('value'), 0, speed, options)

    if (!countItUp.error) {
      countItUp.start()
    } else {
      $(this).text($(this).data('value'))
    }
  })
}
