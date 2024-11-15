import PageContainer from "@larabiz/layouts/components/container/ThemePageContainer";
import { Box, Card, CircularProgress, createTheme, CssBaseline, Grid, PaletteMode, Stack, ThemeProvider, Typography } from "@mui/material";
import React, { useEffect } from "react";
import getCheckoutTheme from "../../../ecommerce/react/pages/checkout/components/getCheckoutTheme";
import { t } from "i18next";

export default function Payment({ page }: { page: string }) {
    const [mode, setMode] = React.useState<PaletteMode>('light');
    const checkoutTheme = createTheme(getCheckoutTheme(mode));
    const query = new URLSearchParams(window.location.search);
    const pageName = page == 'complete' ? t('Complete') : t('Cancel');

    useEffect(() => {
        if (query) {
            const api = `/payment/`;
        }
    }, [query]);

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
