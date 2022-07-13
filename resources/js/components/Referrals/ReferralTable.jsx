import React, { useState } from "react";
import Tabs from "../Tabs";
import Table from "../Table";

const tabs = ["Invites", "Referral Members"];
const headers = [
    [
        { property: "email", label: "Email" },
        { property: "created_at", label: "Created At" },
    ],
    [
        { property: "email", label: "Email" },
        { property: "name", label: "Name" },
        { property: "created_at", label: "Joined on" },
    ],
];
export default function ReferralTable({ invites, referees }) {
    const [currentTab, setCurrentTab] = useState(0);

    return (
        <>
            <Tabs
                tabs={tabs}
                onTabChange={setCurrentTab}
                currentTab={currentTab}
            />
            <Table
                headers={headers[currentTab]}
                data={currentTab === 0 ? invites : referees}
            />
        </>
    );
}
