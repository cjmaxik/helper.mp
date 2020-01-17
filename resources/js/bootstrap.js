/* eslint-disable no-undef */
import * as functions from './functions'

// Outdated Browser
functions.addLoadEvent(() => {
  outdatedBrowser({
    lowerThan: 'transform',
    languagePath: ''
  })

  $('#btnCloseUpdateBrowser, #btnUpdateBrowser').on('click', () => {
    $('#outdated').slideUp('slow')
  })
})

// NProgress
axios.interceptors.request.use(config => {
  NProgress.start()
  return config
}, (error) => {
  NProgress.start()
  return Promise.reject(error)
})

axios.interceptors.response.use(config => {
  NProgress.done()
  return config
}, (error) => {
  NProgress.done()
  return Promise.reject(error)
})

// Axios
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'
axios.defaults.headers.common['X-CSRF-TOKEN'] = window.csrfToken
