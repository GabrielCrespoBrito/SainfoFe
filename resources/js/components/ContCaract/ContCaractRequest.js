export async function search(url) {
  try {
    return await axios.post(url);
  } catch (err) {
    return err;
  }
}

export async function create(url,data) {
  try {
    return await axios.post(url,data);
  } catch (err) {
    return err;
  }
} 

export async function update(url, id, data) {
  try {
    url = url.replace('xxx', id);
    return await axios.post(url, data);
  } catch (err) {
    return err;
  }
} 

export async function destroy(url,id) {
  try {
    url = url.replace('xxx', id);
    return await axios.post(url);
  } catch (err) {
    return err;
  }
} 