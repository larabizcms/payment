import React from "react";
import { Page } from "@admin/features/page/pageSlice";
import { Grid, Icon } from "@mui/material";
import { selectAuthUser } from "@admin/features/selectors";
import { store } from "@local/store";
import { t } from "i18next";
import { LoadingButton } from "@mui/lab";
import Select from "@admin/components/forms/Select";
import Text from "@admin/components/forms/Text";
import { useForm } from "react-hook-form";

type Props = {
    page?: Page,
    uri: string,
};

export default function Balance({ page, uri }: Props) {
    const user = selectAuthUser(store.getState());
    const form = useForm<FormData>();
    const { handleSubmit } = form;
    const [loading, setLoading] = React.useState<boolean>(false);

    const submitForm = (data: FormData) => {
        setLoading(true);


    };

    return (
        <Grid container spacing={2}>
            <Grid item xs={12} md={9}>
                <h4>{t('Available balance: ${{balance}}', { balance: user?.balance || 0 })}</h4>

                <form noValidate onSubmit={handleSubmit(submitForm)}>
                    <Grid container spacing={3}>
                        <Text
                            label={t("Amount")}
                            name="amount"
                            form={form as any}
                            config={{ rules: ['required'] }}
                            type="number"
                        />

                        <Select
                            label={t("Payment Method")}
                            name="method"
                            form={form as any}
                            options={
                                {
                                    paypal: t("PayPal / Visa / Mastercard"),
                                }
                            }
                            config={{ rules: ['required'] }}
                            defaultValue={'paypal'}
                        />

                        <br />

                        <Grid item xs={12}>
                            <LoadingButton
                                //loading={loading}
                                variant="contained"
                                color="primary"
                                type="submit"
                                startIcon={<Icon>add</Icon>}
                            >{t('Add funds')}</LoadingButton>
                        </Grid>
                    </Grid>
                </form>
            </Grid>
        </Grid>
    );
}
