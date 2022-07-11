import React from "react";
import Authenticated from "@/Layouts/Authenticated";
import { Head } from "@inertiajs/inertia-react";
import InviteForm from "@/components/Referrals/InviteForm";

export default function Referrals({ auth, referralCode }) {
    return (
        <Authenticated
            auth={auth}
            header={
                <h2 className="font-semibold text-xl text-gray-800 leading-tight">
                    Referrals
                </h2>
            }
        >
            <Head title="Referrals" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div className="bg-white shadow-sm sm:rounded-lg">
                        <div className="p-6 bg-white border-b border-gray-200">
                            You're referral code :&nbsp;
                            <strong>{referralCode}</strong>
                            <InviteForm />
                        </div>
                    </div>
                </div>
            </div>
        </Authenticated>
    );
}
