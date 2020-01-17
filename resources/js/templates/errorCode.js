module.exports = function (messages, errorCode) {
  let message = messages[errorCode]
  if (typeof message !== 'string') {
    return 'Oops! Something wrong this this thing'
  }
  return message
}
