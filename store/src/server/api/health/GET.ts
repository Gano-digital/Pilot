import type { Request, Response } from "express";

export default async function handler(_req: Request, res: Response) {
	res.json({
		status: "ok",
		timestamp: new Date().toISOString(),
		message: "Hello World!",
	});
}
