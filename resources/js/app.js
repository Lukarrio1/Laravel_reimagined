import "./bootstrap";
import "./Laravel Reimagined Library/index";
import restClient from "./Laravel Reimagined Library/index";
import { handleNode } from "./Laravel Reimagined Library/nodeHandler";


const playground = async () => {
    const {
        data: { node },
    } = await restClient("srnys1Mg1ovoMDbX3GCxQaY3UHuqiMBDkta8ZuUM9reYeh0YJM", {
        uuid: "kmg9uKHV1VR9eoF1mdl3nahG8CCpSduNdL55C26uvwG6c9ldsH",
    });
    handleNode("");
    console.log(node);
};

playground();
