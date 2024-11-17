import PageContainer from "@larabiz/layouts/components/container/ThemePageContainer";
import { createTheme, CssBaseline, ThemeProvider } from "@mui/material";
import React, { useEffect } from "react";
import { t } from "i18next";
import { useParams } from "react-router-dom";
import { getMessageInError } from "@larabiz/helpers/helper";
import { useAppDispatch } from "@larabiz/hooks/hooks";
import { cancel, complete } from "@larabiz/features/payment/payment/paymentActions";
import { useSelector } from "react-redux";
import { RootState } from "@local/store";
import LoadingCenterPage from "@larabiz/layouts/components/LoadingCenterPage";

export default function Payment({ page }: { page: string }) {
    const { module, transactionId } = useParams();
    const checkoutTheme = createTheme();
    const pageName = page == 'complete' ? t('Complete') : t('Cancel');
    const dispatch = useAppDispatch();
    const { loading } = useSelector((state: RootState) => state.payment);

    const redirectHandler = (res: any) => {
        if (res.success) {
            window.location.href = '/admin-cp/profile?success=true'+ (page == 'complete' ? '&message='+ res.payload.message : '');
        } else {
            const error = getMessageInError(res.payload);
            window.location.href = '/admin-cp/profile?success=false&message=' + error;
        }
    }

    useEffect(() => {
        if (window.location.search && transactionId && !loading) {
            const query = new URLSearchParams(window.location.search);

            const data = {
                ...Object.fromEntries(query.entries()),
                transaction_id: transactionId as string,
                module: module as string,
            };

            if (page == 'complete') {
                dispatch(complete(data))
                    .then(redirectHandler)
                    .catch(redirectHandler);
            } else {
                dispatch(cancel(data))
                    .then(redirectHandler)
                    .catch(redirectHandler);
            }
        }
    }, [module, transactionId]);

    return (
        <ThemeProvider theme={checkoutTheme}>
            <CssBaseline />

            <PageContainer title={pageName} description={pageName}>
                <LoadingCenterPage />
            </PageContainer>
        </ThemeProvider>
    );
}
