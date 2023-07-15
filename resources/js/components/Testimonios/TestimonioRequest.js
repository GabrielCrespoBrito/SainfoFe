import getUrl from "../getUrl";

export async function search(url) {
  try {
    return await axios.post(url);
  } catch (err) {
    return err;
  }
}

export async function create(url,data) {
  try {
    // return await axios.post(getUrl('admin/pagina/testimonios'), data);
    return await axios.post(url,data);
  } catch (err) {
    return err;
  }
} 

export async function update(url, id, data) {
  try {
    // return await axios.post(url, getUrl(`admin/pagina/testimonios/${id}/edit`), data);
    url = url.replace('xxx', id);
    return await axios.post(url, data);
  } catch (err) {
    return err;
  }
} 

export async function destroy(url,id) {
  try {
    // return await axios.post(getUrl(`admin/pagina/testimonios/${id}/destroy`));
    url = url.replace('xxx', id);
    return await axios.post(url);
  } catch (err) {
    return err;
  }
} 