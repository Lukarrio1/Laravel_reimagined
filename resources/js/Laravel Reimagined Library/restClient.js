import axios from "axios";

export const restClient = async (route_uuid='', route_params = {}, data_to_send = {}) => {
    const {
        data: { node },
    } = await axios.get("http://localhost:8000/api/nodes/" + route_uuid);
    if (!node) {
        return -1;
    }
    const {
        properties: { value },
        ...next_props
    } = node;

    return build_rest_client(
        build_rest_url(value?.node_route, route_params),
        value,
        data_to_send,
        node
    );
};

const setUpAuth = (node) => {
    // Get the Bearer token from Session Storage
    const token = sessionStorage.getItem("bearerToken");
    const authentication_level = node?.authentication_level["value"];
    return authentication_level == 1
        ? axios.create({
              headers: {
                  Authorization: `Bearer ${token}`, // Set the Authorization header with the Bearer token
              },
          })
        : axios;
};

const build_rest_url = (url, params) => {
    return Object.keys(params).length == 0
        ? url
        : url
              .split("/")
              .map((seg) => {
                  return translate_params(params)[seg]
                      ? translate_params(params)[seg]
                      : seg;
              })
              .join("/");
};

const translate_params = (params) => {
    const param_keys = Object.keys(params);
    const translation = {};
    param_keys.forEach((param) => {
        translation[`{${param}}`] = params[param];
    });
    return translation;
};

const build_rest_client = (route, route_values, data, node) => {
    switch (route_values["route_method"]) {
        case "get":
            return setUpAuth(node).get(route, data);
            break;
        case "post":
            return setUpAuth(node).post(route, data);
            break;
        case "delete":
            return setUpAuth(node).delete(route, data);
            break;
        case "put":
            return setUpAuth(node).put(route, data);
            break;
    }
};
