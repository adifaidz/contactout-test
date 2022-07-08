import React, { useEffect, useState } from "react";
import Authenticated from "@/Layouts/Authenticated";
import { Head, useForm } from "@inertiajs/inertia-react";
import Select from "react-select/creatable";
import Button from "@/Components/Button";
import Label from "@/Components/Label";
import ValidationErrors from "@/Components/ValidationErrors";
import { z } from "zod";

const validator = z.array(z.string().email());

const customStyles = {
    input: (provided, state) => ({
        ...provided,
        "input:focus": {
            boxShadow: "none",
        },
    }),
};

export default function Referrals({ auth, referralCode }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        emails: [],
    });
    const [emailOptions, setEmailOptions] = useState(
        data?.emails
            ? data.emails.map((email) => ({ label: email, value: email }))
            : []
    );
    const [errorMessage, setErrorMessage] = useState();

    const onHandleChange = (inputEmails, actionMeta) => {
        console.log(inputEmails);
        console.log(actionMeta.action);
        if (
            actionMeta.action === "create-option" ||
            actionMeta.action === "remove-value"
        ) {
            const emails = inputEmails.map((input) => {
                return input.value;
            });

            try {
                validator.parse(emails);
                setData("emails", emails);
                setEmailOptions(inputEmails);
            } catch (error) {
                setErrorMessage("Invalid email, please try again.");
                throw error;
            }
        }
    };

    const handleOnSubmit = (e) => {
        e.preventDefault();
        post(route("referrals.invite"));
    };

    const disableEnter = (e) => {
        if (e.keyCode == "13") {
            e.preventDefault();
            return;
        }
    };

    return (
        <Authenticated
            auth={auth}
            errors={errors}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Referrals
                </h2>
            }
        >
            <Head title="Referrals" />

            <ValidationErrors errors={errors} />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            You're referral code :
                            <strong>{referralCode}</strong>
                            <form
                                onSubmit={handleOnSubmit}
                                onKeyDown={disableEnter}
                            >
                                <div>
                                    <Label forInput="email" value="Email" />
                                    <Select
                                        isClearable
                                        isMulti
                                        options={emailOptions}
                                        onChange={onHandleChange}
                                        onInputChange={onHandleChange}
                                        styles={customStyles}
                                        className="mt-1 block w-full"
                                    />
                                </div>
                                <div className="flex items-center justify-center mt-4 w-70">
                                    {errorMessage ?? <div>{errorMessage}</div>}
                                </div>
                                <div className="flex items-center justify-center mt-4 w-30">
                                    <Button
                                        className="w-full justify-center text-center"
                                        processing={processing}
                                    >
                                        Invite
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </Authenticated>
    );
}
