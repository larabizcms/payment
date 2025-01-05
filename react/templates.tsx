import { Page } from "@admin/features/page/pageSlice";
import Balance from "./pages/balance";
import React from "react";

export const templates = {
    profile_balance: (page: Page, uri: string) => <Balance page={page} uri={uri} />,
}
