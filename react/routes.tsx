import React from "react";
import { RouteObject } from "react-router-dom";
import Payment from "./pages/payment";

const routes: RouteObject[] = [
    {
        path: "/payment/:transactionId/complete",
        element: <Payment page="complete" />
    },
    {
        path: "/payment/:transactionId/cancel",
        element: <Payment page="cancel" />
    }
];

export default routes;
