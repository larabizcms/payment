import React from "react";
import { RouteObject } from "react-router-dom";
import Payment from "./pages/payment";
import Checkout from "./pages/components/PaymentForm";

const routes: RouteObject[] = [
    {
        path: "/payment/:module/checkout/:id",
        element: <Checkout />
    },
    {
        path: "/payment/:module/complete/:transactionId",
        element: <Payment page="complete" />
    },
    {
        path: "/payment/:module/cancel/:transactionId",
        element: <Payment page="cancel" />
    },
];

export default routes;
