export interface Selectors {
	container: HTMLElement;
	tablist: HTMLElement;
	tabs: NodeListOf<HTMLElement>;
	tabpanels: NodeListOf<HTMLElement>;
	activeTab: HTMLElement;
}

export interface TabJSON {
	value: string;
	expiry: string;
}

export interface Tabs {
	mainTab: string;
	subTab?: string;
}
