export interface GeneralSetting {
    id: number;
    site_logo: string;
    site_title: string;
    date_format: string;
    timezone: string;
    developed_by: string | null;
    created_at: string;
    updated_at: string;

    [key: string]: any;
}

export interface GeneralSettingForm {
    site_logo: File | null;
    site_title: string;
    date_format: string;
    developed_by: string;

    [key: string]: any;
}
