import React from "react";
import ReactDOM from "react-dom";
import "./assets/scss/index.scss";
import { App } from "./components/app/App";
import * as serviceWorker from "./serviceWorker";

ReactDOM.render(<App />, document.getElementById("root"));

serviceWorker.unregister();
