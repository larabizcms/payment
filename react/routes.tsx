import React from "react";
import { RouteObject } from "react-router-dom";
import Payment from "./pages/payment";

const routes: RouteObject[] = [
    {
        path: "/payment/:module/complete/:transactionId",
        element: <Payment page="complete" />
    },
    {
        path: "/payment/:module/cancel/:transactionId",
        element: <Payment page="cancel" />
    }
];

export default routes;
