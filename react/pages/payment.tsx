import PageContainer from "@larabiz/layouts/components/container/ThemePageContainer";
import { Card, CircularProgress, createTheme, CssBaseline, Grid, Stack, ThemeProvider, Typography } from "@mui/material";
import React, { useEffect } from "react";
import { t } from "i18next";
import { useParams } from "react-router-dom";
import { getMessageInError } from "@larabiz/helpers/helper";
import { useAppDispatch } from "@larabiz/hooks/hooks";
import { cancel, complete } from "@larabiz/features/payment/payment/paymentActions";
import { useSelector } from "react-redux";
import { RootState } from "@local/store";

export default function Payment({ page }: { page: string }) {
    const { transactionId } = useParams();
    const checkoutTheme = createTheme();
    const pageName = page == 'complete' ? t('Complete') : t('Cancel');
    const dispatch = useAppDispatch();
    const { loading } = useSelector((state: RootState) => state.payment);

    const redirectHandler = (res: any) => {
        if (res.success) {
            window.location.href = '/admin-cp/profile?success=true';
        } else {
            const error = getMessageInError(res);
            window.location.href = '/admin-cp/profile?success=false&error=' + error;
        }
    }

    useEffect(() => {
        if (window.location.search && transactionId && !loading) {
            const query = new URLSearchParams(window.location.search);

            const data = {
                ...Object.fromEntries(query.entries()),
                transaction_id: transactionId as string,
                module: 'ecommerce',
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
    }, [transactionId]);

    return (
        <ThemeProvider theme={checkoutTheme}>
            <CssBaseline />

            <PageContainer title={pageName} description={pageName}>
                <Grid container direction="column" >
                    <Grid item xs={12}>
                        <Grid
                            item
                            xs={12}
                            container
                            justifyContent="center"
                            alignItems="center"
                            sx={{ minHeight: { xs: 'calc(100vh - 210px)', sm: 'calc(100vh - 134px)', md: 'calc(100vh - 112px)' } }}
                        >
                            <Grid item>
                                <Card>
                                    <Grid container spacing={3} sx={{ p: 3 }}>
                                        {/* Loading center page */}
                                        <Grid item xs={12}>
                                            <Stack
                                                direction="row"
                                                justifyContent="space-between"
                                                alignItems="baseline"
                                                sx={{ mb: { xs: -0.5, sm: 0.5 } }}
                                            >
                                                <Typography variant="h5">{t('Processing, Please wait...')}</Typography>
                                            </Stack>
                                        </Grid>
                                        <Grid item xs={12} sx={{ textAlign: 'center' }}>
                                            <CircularProgress />
                                        </Grid>
                                    </Grid>
                                </Card>
                            </Grid>
                        </Grid>
                    </Grid>
                </Grid>
            </PageContainer>
        </ThemeProvider>
    );
}
