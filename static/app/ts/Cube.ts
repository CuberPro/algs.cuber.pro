class Cube {

	static MIN_SIZE = 2;
	static MAX_SIZE = 9;

	static U = 'u';
	static R = 'r';
	static F = 'f';
	static D = 'd';
	static L = 'l';
	static B = 'b';
	static NONE = 'n';

	private _size: number;
	private _stickers: string[][];

	constructor(size: number) {
		size = parseInt(size.toFixed(0));
		if (size < Cube.MIN_SIZE || size > Cube.MAX_SIZE) {
			size = 3;
		}
		this._size = size;
		this.reset();
	}

	get size() {
		return this._size;
	}

	public reset(): void {
		let stickersPerFace = this.size * this.size;
		this._stickers = [
			new Array(stickersPerFace + 1).join(Cube.U).split(''),
			new Array(stickersPerFace + 1).join(Cube.R).split(''),
			new Array(stickersPerFace + 1).join(Cube.F).split(''),
			new Array(stickersPerFace + 1).join(Cube.D).split(''),
			new Array(stickersPerFace + 1).join(Cube.L).split(''),
			new Array(stickersPerFace + 1).join(Cube.B).split('')
		];
	}

	public getStickersString(): string {
		return this._stickers.map(function(item) { return item.join('') }).join('');
	}

	public setStickersString(stickers: string): boolean {
		let rangeStr = Cube.U + Cube.R + Cube.F + Cube.D + Cube.L + Cube.B + Cube.NONE;
		let stickersPerFace = this.size * this.size;
		let stickersTotal = stickersPerFace * 6;
		let regex = new RegExp('^[' + rangeStr + ']{' + stickersTotal + '}$');
		if (!regex.test(stickers)) {
			return false;
		}

		this._stickers = [
			stickers.substr(0, stickersPerFace).split(''),
			stickers.substr(stickersPerFace, stickersPerFace).split(''),
			stickers.substr(2 * stickersPerFace, stickersPerFace).split(''),
			stickers.substr(3 * stickersPerFace, stickersPerFace).split(''),
			stickers.substr(4 * stickersPerFace, stickersPerFace).split(''),
			stickers.substr(5 * stickersPerFace, stickersPerFace).split('')
		];
		return true;
	}
}