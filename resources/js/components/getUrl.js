const BaseUrl = 'https://sainfo.pe/';
// const BaseUrl = 'http://localhost:8000/';

export default function getUrl(url = "") {
  return BaseUrl + url;
}