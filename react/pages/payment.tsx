import PageContainer from "@admin/layouts/components/container/ThemePageContainer";
import { createTheme, CssBaseline, ThemeProvider } from "@mui/material";
import React, { useEffect } from "react";
import { t } from "i18next";
import { useParams } from "react-router-dom";
import { getMessageInError, showNotification } from "@admin/helpers";
import { useAppDispatch } from "@admin/hooks/hooks";
import { cancel, complete } from "@admin/features/payment/payment/paymentActions";
import { useSelector } from "react-redux";
import { RootState } from "@local/store";
import LoadingCenterPage from "@admin/layouts/components/LoadingCenterPage";

export default function Payment({ page }: { page: string }) {
    const { module, transactionId } = useParams();
    const checkoutTheme = createTheme();
    const pageName = page == 'complete' ? t('Complete') : t('Cancel');
    const dispatch = useAppDispatch();
    const { loading } = useSelector((state: RootState) => state.payment);

    const redirectHandler = (res: any) => {
        let redirectUrl = res.payload?.data?.redirect_url ?? '/client/balance';

        if (res.payload?.success) {
            if (res.payload?.message) {
                showNotification(res.payload.message, 'success');
            }

            setTimeout(() => {
                window.location.href = redirectUrl + '?success=true';
            }, 500);
        } else {
            const error = getMessageInError(res.payload);
            showNotification(error, 'error');

            setTimeout(() => {
                window.location.href = redirectUrl + '?success=false';
            }, 500);
        }
    }

    useEffect(() => {
        if (transactionId && !loading) {
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
    }, [module, transactionId, dispatch]);

    return (
        <ThemeProvider theme={checkoutTheme}>
            <CssBaseline />

            <PageContainer title={pageName} description={pageName}>
                <LoadingCenterPage />
            </PageContainer>
        </ThemeProvider>
    );
}
