import getUrl from "../getUrl";

export async function search(url) {
  try {
    // return await axios.post(getUrl('admin/pagina/clientes/search'));
    return await axios.post(url);
  } catch (err) {
    return err;
  }
}