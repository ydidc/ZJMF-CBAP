
/* 通用接口API */

// 获取国家列表
function getCountry (params) {
  return Axios.get(`/country`, params)
}
// 获取支付接口
function getPayList () {
  return Axios.get('/gateway')
}
// 获取公共配置
function getCommon () {
  return Axios.get('/common')
}
// 获取登录信息
function getLoginInfo () {
  return Axios.get('/login')
}
// 获取验证码
function getCaptcha () {
  return Axios.get('/captcha')
}
// 登录
function logIn (params) {
  return Axios.post('/login', params)
}
// 退出登录
function logout () {
  return Axios.post('/logout')
}
// 获取权限
function getAuthRole () {
  return Axios.get('/auth')
}