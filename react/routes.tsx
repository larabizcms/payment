import React from "react";
import { RouteObject } from "react-router-dom";
import Payment from "./pages/payment";

const routes: RouteObject[] = [
    {
        path: "/payment/complete/:transactionId",
        element: <Payment page="complete" />
    },
    {
        path: "/payment/:module/cancel/:transactionId",
        element: <Payment page="cancel" />
    }
];

export default routes;
