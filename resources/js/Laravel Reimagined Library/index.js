console.log("hello there");
import axios from "axios";
import { resetClient } from './restClient'

  sessionStorage.setItem(
      "bearerToken",
      "2|jAdtGx3kHvNiPsPeQ3W96kymTb2VCnryVfQYkTtv1a753bc6"
  );


const getNodes = async (node_url) => {
    try {
    const nodes_data = await axios.get(node_url);
    const nodes = nodes_data?.data?.nodes;

    const { data } = await resetClient(
        "kZ5ZSVmv6BWUYWbPI0is2N3kiy6agWIm4fZw4LUBUbx2xi2Reo",
        nodes
    );
    console.log(data);
    } catch (error) {
        console.log(error)
    }

};


getNodes("http://localhost:8000/api/nodes");