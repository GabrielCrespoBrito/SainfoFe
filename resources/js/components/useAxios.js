import axios from "axios";
import { useEffect, useState } from "react";
import getUrl from "../getUrl";

export default function useAxios(url) {

  const [isSearching, setIsSearching] = useState(true);
  const [data, setData] = useState(null);
  
  useEffect(() => {
    setIsSearching(true);
    try {
      axios
      .post(getUrl(url))
      .then((res) => {
        setData(() => res.data )
        setIsSearching(false);
      })
    } catch (err) {
      return err;
      setIsSearching(false);
    }
  }, [url])
    
  return [
    isSearching,
    data
  ]
}