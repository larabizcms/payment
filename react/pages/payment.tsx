import PageContainer from "@larabiz/layouts/components/container/ThemePageContainer";
import { Card, CircularProgress, createTheme, CssBaseline, Grid, Stack, ThemeProvider, Typography } from "@mui/material";
import React, { useEffect } from "react";
import { t } from "i18next";
import { useParams } from "react-router-dom";
import http from "@larabiz/http-common";
import { getMessageInError, showNotification } from "@larabiz/helpers/helper";

export default function Payment({ page }: { page: string }) {
    const { transactionId } = useParams();
    const checkoutTheme = createTheme();
    const pageName = page == 'complete' ? t('Complete') : t('Cancel');

    useEffect(() => {
        if (window.location.search) {
            const query = new URLSearchParams(window.location.search);
            const api = `/payment/ecommerce/` + page + `/` + transactionId;
            const data = Object.fromEntries(query.entries());
            console.log(data);

            http.post(api, data).then((res) => {
                if (res.data.success === true) {
                    setTimeout(() => {
                        if (res.data.data.redirect_url) {
                            window.location.href = res.data.data.redirect_url;
                        } else {
                            window.location.href = '/';
                        }
                    }, 500);
                }
            })
                .catch((res: any) => {
                    showNotification(getMessageInError(res), 'error');
                });
        }
    }, [window.location.search]);

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
