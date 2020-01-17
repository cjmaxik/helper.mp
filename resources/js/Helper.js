/* eslint-disable camelcase,no-undef */
import * as functions from './functions'

let $div_playerSearch = $('div#player_search')
let $div_playerModal = $('div#player_modal')

export default class Helper {
  constructor () {
    this.data = []

    // Set the storage
    Helper.initStorage()
    this.documentReady()
  }

  static initStorage () {
    Lockr.prefix = 'helper-'
  }

  static jarallaxInit () {
    jarallax($('.jarallax'), {
      speed: 0.2,
      type: 'scroll-opacity',
      automaticResize: true
    })

    console.log('Jarallax has been initialised!')
  }

  static headerInit () {
    functions.countItUp()
  }

  static bootstrapInit () {
    $('[data-toggle="tooltip"]').tooltip({ template: '<div class="tooltip md-tooltip"><div class="tooltip-arrow md-arrow"></div><div class="tooltip-inner md-inner-main"></div></div>' })

    console.log('Bootstrap has been initialised!')
  }

  static generateTemplate (type, data) {
    if (type === 'player') {
      console.log(data)
      type = data.error ? 'fail' : 'success'
    }

    let template = require('./templates/' + type + '.hbs')

    let finalData = {
      data: data ? data.response : null,
      ui: window.ui
    }

    console.log(finalData)

    return template(finalData)
  }

  initMode () {
    let mode = Lockr.get('mode', 'auto')
    this.checkMode(mode)

    $('form#search_mode').on('change', event => {
      this.checkMode(event.target.value)
    })

    functions.addLoadEvent(() => {
      $(mode).prop('checked', true)
    })
  }

  checkMode (mode) {
    let mode_el = `input#mode_${functions.slugify(mode)}`

    $(mode_el).prop('checked', true)
    Lockr.set('mode', mode)

    this.data.mode = mode
    console.log('mode changed to ' + this.data.mode)
  }

  documentReady () {
    // noinspection JSUnusedLocalSymbols
    $(document).ready($ => {
      console.log('Ready for action!')

      this.initMode()
      this.historyInit()
      this.eventsInit()

      Helper.bootstrapInit()

      this.videoModalInit()
      this.orderChange()

      this.searchInit()

      if (!bowser.msedge) {
        Helper.jarallaxInit()
      }

      this.cookieConsent()
      Helper.headerInit()

      // noinspection JSUnresolvedVariable
      NProgress.done()
    })
  }

  historyInit () {
    this.data.search_history = Lockr.get('history', [])
    let search_history = this.data.search_history

    this.data.search_history.forEach(function (item, index) {
      if (typeof item === 'string') {
        search_history[index] = {
          needle: item,
          mode: 'auto'
        }
      }
    })

    let hist = ''

    if (search_history.length) {
      search_history.forEach(function (item) {
        if (typeof item === 'object') {
          item.mode = item.mode
            .replace('steamID', 'steam_id')
            .replace('customURL', 'steam_url')
            .replace('truckersmpID', 'truckersmp_id')
          if (item.mode === null) {
            item.mode = 'auto'
          }

          let icon = null
          switch (item.mode) {
            case 'steam_id':
              icon = 'steam'
              break

            case 'steam_url':
              icon = 'link-variant'
              break

            case 'truckersmp_id':
              icon = 'truck'
              break
          }

          if (item.mode === 'auto') {
            hist += `<span class="hist_item waves-effect" data-needle="${item.needle}" data-mode="${item.mode}">${item.needle}</span>`
          } else {
            hist += `<span class="hist_item waves-effect" data-needle="${item.needle}" data-mode="${item.mode}"><span class="iconify" data-icon="mdi:${icon}"></span>&nbsp;${item.needle}</span>`
          }
        } else {
          hist += `<span class="hist_item waves-effect" data-needle="${item}">${item}</span>`
        }
      })
    } else {
      // noinspection JSUnresolvedVariable
      hist = window.no_history_message
    }

    Lockr.set('history', search_history)
    this.data.search_history = search_history
    $('span#history').html(hist)
  }

  eventsInit () {
    this.videoModalInit()

    let self = this
    $('span#history').on('click', 'span.hist_item', function () {
      let needle = $(this).data('needle')
      let mode = $(this).data('mode')

      if (!mode) {
        mode = 'auto'
      }

      if (needle) {
        self.checkMode(mode)
        $('input#search_needle').val(needle)
        $('#search_button').click()
      }
    })

    $('button#dismissTranslation').on('click', function () {
      $('div#dismissTranslationDiv')
        .removeClass('d-none')
        .removeClass('d-md-block')
        .slideUp('slow')

      axios
        .post('/translationClose')
        .then(response => {
          console.log(response)
        })
        .catch(error => {
          console.log(error)
        })
    })

    $('a#navbar-brand').on('click', event => {
      event.preventDefault()
      $('html, body').animate({ scrollTop: 0 })
    })

    $('a.darkModeToggle').on('click', event => {
      event.preventDefault()

      this.darkModeToggle()
    })
  }

  videoModalInit () {
    $('button#videoOpen').on('click', event => {
      event.preventDefault()
      $('div.videoModal').modal('show')
      // noinspection JSUnresolvedVariable
      $('iframe#videoModalFrame').attr('src', window.help_src)
    })

    $('div.videoModal').on('hidden.bs.modal', () => {
      $('iframe#videoModalFrame').removeAttr('src')
    })
  }

  orderChange () {
    $('input#orderChange').on('change', () => {
      // noinspection JSUnresolvedVariable
      window.location.href = window.changeOrderURL
    })
  }

  searchInit () {
    $('#search_needle').keypress(event => {
      let keycode = event.keyCode ? event.keyCode : event.which
      if (keycode === 13) {
        this.searchUser()

        event.stopPropagation()
        event.preventDefault()
        return false
      }
    })

    $('#search_button').on('click', event => {
      event.preventDefault()
      this.searchUser()
      $('#search_needle').val('')
    })

    $div_playerModal.on('hidden.bs.modal', () => {
      $div_playerModal.html('')
    })
  }

  searchUser () {
    let needle = functions.cleanArray(
      $('input#search_needle')
        .val()
        .trim()
        .split('/')
    )
    let mode = this.data.mode

    console.log('Searching by needle ' + needle + ' with mode ' + mode)

    $('#slowLoading').slideUp()

    function renderResults (template) {
      $div_playerModal.html(template).modal('show')
      $div_playerSearch.modal('hide')
    }

    if (!needle) {
      $('#error').slideDown()
      $div_playerSearch.modal('hide')
    } else {
      $('#error').slideUp()
      $div_playerSearch.modal('show')
      $('html, body').animate({ scrollTop: 0 })

      let slugifyMode = functions.slugify(mode)
      let callTimeout = setTimeout(function () {
        $('#slowLoading').slideDown()
      }, 10000)

      let self = this
      setTimeout(function () {
        axios
          .post('/search', {
            needle,
            mode: slugifyMode
          })
          .then(response => {
            clearTimeout(callTimeout)
            console.log(response)

            let template = Helper.generateTemplate('player', response.data)

            renderResults(template)
            functions.autoLink('.autolink')

            if (!response.data.error) {
              self.addToHistory({ needle, mode: slugifyMode })
              $('#search_needle').val('')
            }
          })

          .catch(error => {
            clearTimeout(callTimeout)
            console.error(error)

            let template = Helper.generateTemplate('error', null)
            renderResults(template)
          })
      }, 500)
    }
  }

  addToHistory (historyItem) {
    let search_history = this.data.search_history

    if (search_history.length >= 10) {
      search_history = search_history
        .reverse()
        .slice(0, 9)
        .reverse()
    }

    search_history.push(historyItem)
    search_history = search_history.filter(
      (item, index, self) =>
        index ===
        self.findIndex(t => t.needle === item.needle && t.mode === item.mode)
    )

    Lockr.set('history', search_history)

    this.data.search_history = search_history
    this.historyInit()
  }

  cookieConsent () {
    if (!functions.getCookie('cookie_consent_dissmissed')) {
      $('div#modalCookie').modal('show')

      $('a#dismissCookie').on('click', function () {
        functions.setCookie('cookie_consent_dissmissed', true)
      })
    }
  }

  darkModeToggle () {
    $('body').toggleClass('dark')

    $('#darkMode_dark').toggle()
    $('#darkMode_white').toggle()

    window.darkMode = !window.darkMode

    axios
      .post('/changeDarkMode')
      .then(response => {
        console.log(response)
      })
      .catch(error => {
        if (error.response) {
          if (error.response.status === 429) {
            alert('Stop right there, criminal scum!')
          }
        }
      })
  }
}
